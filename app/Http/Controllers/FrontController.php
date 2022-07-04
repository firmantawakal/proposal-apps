<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Schedule;
use App\Models\Season;
use App\Models\Club;
use App\Models\Posts;

class FrontController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $isActiveSeason = DB::table('season')->where('isActive',1)->first();

        // $club = Club::all();
        // $player = Player::all();
        // $season = DB::table('season')->get();
        $all_schedule = Schedule::
                    leftJoin('club AS a', 'schedule.id_club_a', '=', 'a.id')
                    ->leftJoin('club AS b', 'schedule.id_club_b', '=', 'b.id')
                    ->join('season', 'schedule.id_season', '=', 'season.id')
                    ->where('season.id',$isActiveSeason->id)
                    ->where('isFinish',0)
                    ->orderBy('time','DESC')
                    ->select(
                        'a.club_name as club_a',
                        'b.club_name as club_b',
                        'a.image as image_a',
                        'b.image as image_b',
                        'a.stadium as stadium_a',
                        'b.stadium as stadium_b',
                        'a.coach as coach_a',
                        'b.coach as coach_b',
                        'schedule.*','season.*')->get();

        $all_finish = Schedule::
                        leftJoin('club AS a', 'schedule.id_club_a', '=', 'a.id')
                        ->leftJoin('club AS b', 'schedule.id_club_b', '=', 'b.id')
                        ->join('season', 'schedule.id_season', '=', 'season.id')
                        ->where('season.id',$isActiveSeason->id)
                        ->where('isFinish',1)
                        ->orderBy('time','DESC')
                        ->select(
                            'a.club_name as club_a',
                            'b.club_name as club_b',
                            'a.image as image_a',
                            'b.image as image_b',
                            'a.stadium as stadium_a',
                            'b.stadium as stadium_b',
                            'a.coach as coach_a',
                            'b.coach as coach_b',
                            'schedule.*','season.*')->get();

        $last_finish = Schedule::
                        leftJoin('club AS a', 'schedule.id_club_a', '=', 'a.id')
                        ->leftJoin('club AS b', 'schedule.id_club_b', '=', 'b.id')
                        ->join('season', 'schedule.id_season', '=', 'season.id')
                        ->where('season.id',$isActiveSeason->id)
                        ->where('isFinish',1)
                        ->limit(1)
                        ->orderBy('time','DESC')
                        ->select(
                            'a.club_name as club_a',
                            'b.club_name as club_b',
                            'a.image as image_a',
                            'b.image as image_b',
                            'a.stadium as stadium_a',
                            'b.stadium as stadium_b',
                            'a.coach as coach_a',
                            'b.coach as coach_b',
                            'schedule.*','season.*')->first();

        $latest_post = Posts::
                        leftJoin('users', 'posts.id_author', '=', 'users.id')
                        ->leftJoin('categories', 'posts.id_category', '=', 'categories.id')
                        ->where('posts.status',1)
                        ->orderBy('posts.updated_at','DESC')
                        ->limit(3)
                        ->select(
                            'posts.updated_at as post_time',
                            'categories.name as category_name',
                            'users.*','categories.*','posts.*')->get();

        return view('frontend.home', compact('all_schedule', 'all_finish','last_finish', 'latest_post'));
    }

    public function jadwal()
    {
        $isActiveSeason = DB::table('season')->where('isActive',1)->first();
        $all_schedule = Schedule::
                    leftJoin('club AS a', 'schedule.id_club_a', '=', 'a.id')
                    ->leftJoin('club AS b', 'schedule.id_club_b', '=', 'b.id')
                    ->join('season', 'schedule.id_season', '=', 'season.id')
                    ->where('season.id',$isActiveSeason->id)
                    ->where('isFinish',0)
                    ->orderBy('time','DESC')
                    ->select(
                        'a.club_name as club_a',
                        'b.club_name as club_b',
                        'a.image as image_a',
                        'b.image as image_b',
                        'a.stadium as stadium_a',
                        'b.stadium as stadium_b',
                        'a.coach as coach_a',
                        'b.coach as coach_b',
                        'schedule.*','season.*')->get();

        return view('frontend.jadwal', compact('all_schedule'));
    }

    public function hasil()
    {
        $isActiveSeason = DB::table('season')->where('isActive',1)->first();
        $all_schedule = Schedule::
                    leftJoin('club AS a', 'schedule.id_club_a', '=', 'a.id')
                    ->leftJoin('club AS b', 'schedule.id_club_b', '=', 'b.id')
                    ->join('season', 'schedule.id_season', '=', 'season.id')
                    ->where('season.id',$isActiveSeason->id)
                    ->where('isFinish',1)
                    ->orderBy('time','DESC')
                    ->select(
                        'a.club_name as club_a',
                        'b.club_name as club_b',
                        'a.image as image_a',
                        'b.image as image_b',
                        'a.stadium as stadium_a',
                        'b.stadium as stadium_b',
                        'a.coach as coach_a',
                        'b.coach as coach_b',
                        'schedule.*','season.*')->get();

        return view('frontend.hasil', compact('all_schedule'));
    }

    public function detail($slug)
    {
        $detail = Posts::
                    leftJoin('users', 'posts.id_author', '=', 'users.id')
                    ->leftJoin('categories', 'posts.id_category', '=', 'categories.id')
                    ->where('slug',$slug)
                    ->select(
                        'posts.updated_at as post_time',
                        'categories.name as category_name',
                        'users.*','categories.*','posts.*')->first();

        $image = DB::table('images')->where('id_post',$detail->id)->get();

        $latest_post = Posts::
                        leftJoin('users', 'posts.id_author', '=', 'users.id')
                        ->leftJoin('images', 'images.id_post', '=', 'posts.id')
                        ->leftJoin('categories', 'posts.id_category', '=', 'categories.id')
                        ->where('posts.status',1)
                        ->orderBy('posts.updated_at','DESC')
                        ->limit(5)
                        ->select(
                            'users.name as user',
                            'images.name as images',
                            'posts.updated_at as post_time',
                            'categories.name as category_name',
                            'categories.*','posts.*')->get();
        // dd($latest_post);
        return view('frontend.detail-post', compact('detail','image','latest_post'));
    }

    public function klasemen()
    {
        $rank = array();
        $isActiveSeason = DB::table('season')->where('isActive',1)->first();
        $club = Club::all();
        foreach ($club as $cl) {
            $result = DB::table('schedule as s')
                    ->join('club as ca','s.id_club_a','ca.id')
                    ->join('club as cb','s.id_club_b','cb.id')
                    ->join('season', 's.id_season', 'season.id')
                    ->where('season.id',$isActiveSeason->id)
                    ->whereRaw('ca.id = '.$cl->id.' OR cb.id = '.$cl->id)
                    // ->orWhere('cb.id',$cl->id)
                    ->get();

            $play=0;
            $m=0;
            $s=0;
            $k=0;
            $pts=0;
            $goal=0;
            $goalLose=0;

            foreach ($result as $res) {
                $play+=1;
                if ($res->id_club_a == $cl->id) {
                    $pts += $res->point_a;
                    if ($res->point_a > $res->point_b) {
                        $m += 1;
                    }elseif ($res->point_a == $res->point_b) {
                        $s += 1;
                    }else{
                        $k += 1;
                    }
                    $goal += $res->score_a;
                    $goalLose += $res->score_b;
                }
                elseif ($res->id_club_b == $cl->id) {
                    $pts += $res->point_b;
                    if ($res->point_b > $res->point_a) {
                        $m += 1;
                    }elseif ($res->point_b == $res->point_a) {
                        $s += 1;
                    }else{
                        $k += 1;
                    }
                    $goal += $res->score_b;
                    $goalLose += $res->score_a;
                }
            }

            $rank[] = [
                'club_image' =>$cl->image,
                'club_name' =>$cl->club_name,
                'p' =>$play,
                'm' =>$m,
                's' =>$s,
                'k' =>$k,
                'pm' =>$goal-$goalLose,
                'pts' =>$pts,
            ];
        }

        // sorting array by pts, then pm
        usort($rank, function ($a, $b) {
            if ($a['pts'] == $b['pts']) {
                if ($a['pm'] < $b['pm']) {
                    return 1;
                }
            }
            return $a['pts'] < $b['pts'] ? 1 : -1;
        });
        return view('frontend.klasemen', compact('rank'));
    }


}
