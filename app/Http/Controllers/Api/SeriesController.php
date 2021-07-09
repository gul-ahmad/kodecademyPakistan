<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Series;

use Validator;

use Intervention\Image\ImageManagerStatic as Image;
use Symfony\Component\HttpFoundation\Response;

use Illuminate\Validation\ValidationException;




class SeriesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $usersList =Series::all();

            return $usersList; 
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,$id)
    {
        $id =$request->id;
        $series =Series::findOrFail($id);
        $seriesCurentPhoto= $series->logo;
      //  dd($seriesCurentPhoto);
      //  dd($request->all());
        $image =$request->logo;
      //  dd($image);
        $title =$request->title;
        $description =$request->description;
       
      // $currentPhoto = $request->oldlogo;
    // dd($seriesCurentPhoto,$image);
     $this->validate($request, [ 
        'title' => ['required', 'string', 'max:255'],
        'description' => 'required|string',
         // 'logo' => 'sometimes|image|mimes:jpg,png,jpeg,gif,svg|max:2048',
    ]);

   //  if($request->logo)  {
       //Taking this $seriesCurentPhoto from database to compare the current/database value 
       //of photo with the coming new photo from form 
       //If user is changing the photo then IF condtion will work with image validation 
       // IF the user is not changing the logo/image then else conditon will work without image validation 
    if($request->logo != $seriesCurentPhoto){
  //  dd('I am here in the if conditon in api');
        $this->validate($request, [ 
              'logo' => 'sometimes|image|mimes:jpg,png,jpeg,gif,svg|max:2048',
        ]);
    
   
        

         $file = $request->logo;
       
    
        $filename  = time() . '.' . $file->getClientOriginalExtension();
      
         $filePath =('images/series/' . $filename);
        // $img = Image::make($file->path());
         $img = Image::make($file->getRealPath());
         $img->resize(110, 110, function ($const) {
             $const->aspectRatio();
         })->save($filePath);
         //cropped images is going here 
        $cropped = ($filePath);
              //orignal image is going here
         $path = $request->file('logo')->move('app/public/images',$filename); 
        

             $Series = Series::where('id', $id)
                ->update([
                    'title' => $request->title,
                    'description' => $request->description,
                    'logo'=>$cropped,
                    ]);  

            return ['message'=>'Series Updated successfully'];
     }
     else {

    //   dd('I am here in the else conditon in api');
        


        $Series = Series::where('id', $id)
        ->update([
            'title' => $request->title,
            'description' => $request->description,
           // 'logo'=>$cropped,
            ]);  
         return ['message'=>'Series Updated successfully'];
     }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
      $series =Series::findOrFail($id);
      $series->delete();

    }
}
