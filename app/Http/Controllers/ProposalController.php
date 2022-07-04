<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Proposal;
use App\Models\Department;
use Session;

class ProposalController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $accessLevel = 1; //clerk
        if($accessLevel==1){
            $userId = Session::get('user')->id;
            $category = Category::all();
            $proposals = Proposal::join('category','category.id','=','proposal.category_id')
                            ->selectRaw('proposal.*, category.category_name')
                            ->where('user_id', $userId)
                            ->orderBy('proposal.created_at', 'DESC')
                            ->get();
            $nomor_surat = $this->get_nomor();

            return view('proposal/my_proposal', compact('proposals','category','nomor_surat'));
        }
        else{
            redirect(route('home'));
        }
    }

    public function review($accessLevel = 2)
    {
        if($accessLevel==1 || in_array($accessLevel,Session::get('level')))
        {
            redirect(route('home'));
        }

        $userId = Session::get('user')->id;
        $departmentId = Session::get('position')->department_id;
        $category = Category::all();
        $proposals1 = Proposal::
                        join('category','category.id','=','proposal.category_id')
                        ->join('users','users.id','=','proposal.user_id')
                        ->leftJoin('users as u_rev','u_rev.id','=','proposal.review_id')
                        ->leftJoin('users as u_app1','u_app1.id','=','proposal.approve1_id')
                        ->leftJoin('users as u_app2','u_app2.id','=','proposal.approve2_id')
                        ->join('position','position.id','=','users.position_id')
                        ->join('department','department.id','=','position.department_id')
                        ->where('proposal.user_id','!=',$userId)
                        ->select('proposal.*',
                            'category.category_name',
                            'category.budget',
                            'users.name as username',
                            'position.position_name',
                            'department.department_name',
                            'u_rev.name as rev_name',
                            'u_app1.name as app1_name',
                            'u_app2.name as app2_name',
                            )
                        ->orderBy('proposal.created_at', 'DESC');

        if ($accessLevel==2) {
            $proposals1->where('department.id', $departmentId);
        }

        $props = $proposals1->get();

        $proposals = array();
        foreach ($props as $pr) {
            $isReviewed = false;
            $isApprove1 = false;
            $isApprove2 = false;

            $pr['isReviewed'] = $pr->review_status;
            $pr['isApprove1'] = $pr->approve1_status;
            $pr['isApprove2'] = $pr->approve2_status;

            if ($accessLevel==3 && $pr->review_status==1) {
                if ($pr->cost > $pr->budget) {
                    $pr['textFinish'] = 'Lanjutkan';
                }else{
                    $pr['textFinish'] = 'Terima';
                }
                $proposals[] = $pr;
            }
            elseif ($accessLevel==4 && $pr->cost > $pr->budget && $pr->approve1_status==1) {
                $pr['textFinish'] = 'Terima';
                $proposals[] = $pr;
            }
            elseif($accessLevel==2) {
                $pr['textFinish'] = 'Lanjutkan';
                $proposals[] = $pr;
            }
        }

        return view('proposal/proposal_review', compact('proposals','category','accessLevel'));
    }

    public function review_action(Request $request, $id)
    {
        $accessLevel = $request->accessLevel;
        $user_id = Session::get('user')->id;

        if($accessLevel==2){
            $data['review_id'] = $user_id;
            $data['review_comment'] = $request->comment;
            $data['review_status'] = (int)$request->status;
            $data['review_date'] = date('Y-m-d H:i');
        }elseif ($accessLevel==3) {
            $data['approve1_id'] = $user_id;
            $data['approve1_comment'] = $request->comment;
            $data['approve1_status'] = (int)$request->status;
            $data['approve1_date'] = date('Y-m-d H:i');
        }elseif ($accessLevel==4) {
            $data['approve2_id'] = $user_id;
            $data['approve2_comment'] = $request->comment;
            $data['approve2_status'] = (int)$request->status;
            $data['approve2_date'] = date('Y-m-d H:i');
        }

        // finish when deny
        if ($request->status==0) {
            $data['status'] = 1;
        }

        // finish by check budget
        $budget = $this->cekBudget($request->category_id);

        // if ((int)$request->cost <= $budget && in_array(3, [3])) {
        if ((int)$request->cost <= $budget && in_array(3, Session::get('level'))) {
            $data['status'] = 1;
        }elseif ((int)$request->cost > $budget && in_array(4, Session::get('level'))) {
            $data['status'] = 1;
        }

        // echo json_encode($data);die;

        try {
            Proposal::where('id',$id)->update($data);
            return redirect()->route('proposal.review', $accessLevel)
                        ->with('success','Tindak lanjut proposal berhasil disimpan');
        }  catch (\Exception $ex) {
            dd($ex);
        }
    }

    public function cekBudget($category_id){
        $budget = Category::select('budget')->where('id',$category_id)->first()->budget;
        return $budget;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nomor_surat' => 'required',
            'title' => 'required',
            'cost' => 'required',
            'category_id' => 'required',
            'document' => 'required|max:10000|mimes:jpg,jpeg,bmp,png,gif,svg,pdf'
        ]);

        if ($request->hasfile('document')) {
            $filename = str_replace('/','-',$validated['nomor_surat']);
            $document = $request->file('document');
            $random = mt_rand(1000000000, 9999999999);
            $destinationPath = 'document/';
            $name = $filename.".".$document->getClientOriginalExtension();
            // $name = date('YmdHis-').$random."." . $document->getClientOriginalExtension();
            $document->move($destinationPath, $name);
            $validated['document'] = $name;
        }
        $validated['user_id'] = Session::get('user')->id;
        $this->inc_number();
        Proposal::create($validated);

        return redirect()->route('proposal.index')
                        ->with('succes','Data Berhasil di Tambah');
    }

    function get_nomor(){
        $department_id = Session::get('position')->department->id;
        $nomor = Department::where('id',$department_id)->first();
        $giveNomor = 0;

        if (date('d')=='01') {
            $data['nomor_last'] = 0;
            Department::where('id',$department_id)->update($data);
        }

        if ($nomor->nomor_last == 0) {
            $giveNomor = $nomor->nomor;
        }else{
            $giveNomor = $nomor->nomor_last;
        }

        $struktur = $nomor->struktur;
        $num = sprintf("%02d", $giveNomor);

        $completeNomor = $num.'/'.$struktur.'/'.date('m').'/'.date('Y');
        return $completeNomor;
    }

    function inc_number(){
        $department_id = Session::get('position')->department->id;
        $nomor = Department::where('id',$department_id)->first();
        $last = 0;
        if ($nomor->nomor_last == 0) {
            $last = $nomor->nomor+1;
        }else{
            $last = $nomor->nomor_last+1;
        }

        $data['nomor_last'] = $last;
        Department::where('id',$department_id)->update($data);
    }
}
