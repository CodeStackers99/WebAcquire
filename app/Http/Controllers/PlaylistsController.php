<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Playlist;
use App\Models\SubCourse;
use Illuminate\Http\Request;

class PlaylistsController extends Controller
{
    public function index(Course $course, SubCourse $subCourse)
    {
        if (!($this->checkCourseAndSubCourse($course, $subCourse))) {
            return redirect(route('courses.subcourses.index', $course->slug));
        }
        $playlists = $subCourse->playlists()->search()->with('owner')->latest('updated_at')->paginate(10);
        return view('layouts.Playlist.index', compact(['course', 'subCourse', 'playlists']));
    }

    public function create(Course $course, SubCourse $subCourse)
    {
        if (!($this->checkCourseAndSubCourse($course, $subCourse))) {
            return redirect(route('courses.subcourses.index', $course->slug));
        }
        $this->authorize('create', Playlist::class);
        return view('layouts.Playlist.create', compact(['course', 'subCourse']));
    }

    public function store(Course $course, SubCourse $subCourse, Request $request)
    {
        $this->authorize('create', Playlist::class);

        $rules = [
            'title' => 'required|max:40|unique:playlists',
            'description' => 'required',
            'display_image' => 'required|image|mimes:jpeg,png,jpg|max:512',
            'hours' => 'reuquired|min:0',
        ];
        $this->validate($request, $rules);
        if ($request->hasFile('display_image')) {
            $image = $request->image->store('images/playlists');
        }
        $description = $request->description;
        $description = explode("<div>", $description)[1];
        $description = explode("</div>", $description)[0];
        $playlist = $subCourse->playlists()->create([
            'title' => strtolower($request->title),
            'description' => $description,
            'display_image' => $image,
            'hours' => (int)$request->hours,
            'user_id' => auth()->id,
        ]);
        $playlistName = strtoupper($playlist->name);
        session()->flash('success', "New Playlist $playlistName is created. You can now add Videos To It");
        return redirect(route('courses.subcourses.playlists.videos.create', [$course->slug, $subCourse->slug, $playlist->slug]));
    }

    public function show(Course $course, SubCourse $subCourse, Playlist $playlist)
    {
        if (!($this->checkCourseAndSubCourse($course, $subCourse))) {
            return redirect(route('courses.subcourses.index', $course->slug));
        }
        if (!($playlist->enrolledUsers()->find(auth()->id()))) {
            return view('layouts.Playlist.enroll', compact(['course', 'subCourse', 'playlist']));
        }
        return redirect(route('courses.subcourses.playlists.videos.index', [$course->slug, $subCourse->slug, $playlist->slug]));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Playlist  $playlist
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Playlist $playlist)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Playlist  $playlist
     * @return \Illuminate\Http\Response
     */
    public function destroy(Playlist $playlist)
    {
        //
    }

    private function checkCourseAndSubCourse(Course $course, SubCourse $subCourse)
    {
        if (!($subCourse->course->id === $course->id)) {
            session()->flash('error', "$subCourse->name Subcourse Doesn't Belong to $course->name. You Can Choose Respective SubCourses Available Here");
            return false;
        }
        return true;
    }
}
