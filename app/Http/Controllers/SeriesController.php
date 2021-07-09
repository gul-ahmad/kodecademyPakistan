<?php

namespace App\Http\Controllers;
use Validator;
use App\Models\Series;
use Illuminate\Http\Request;
use Intervention\Image\ImageManagerStatic as Image;
use Symfony\Component\HttpFoundation\Response;

class SeriesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
          return Series::all();
      //   return $series;
     
        //  return ['message'=>'I am in the index function'];
       
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
             $image =$request->logo;
          //  dd($image);

        $this->validate($request, [ 
            'title' => ['required', 'string', 'max:255'],
            'description' => 'required|string',
              'logo' => 'required|image|mimes:jpg,png,jpeg,gif,svg|max:2048',
        ]);


       // $this->validate($request, ['logo' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',]);
        if($request->hasFile('logo'))  {
          //  dd('i am here in the if');
         
            $file = $request->logo;
            $fileName = time() . '.'.$file->getClientOriginalExtension();
         //   dd($fileName);

          //  $filePath = ('public/app/images/thumbnails');
          //  $filePath = public_path('app/public/images/thumbnails/');


          
           $filename  = time() . '.' . $file->getClientOriginalExtension();
         /* $path = public_path('images/series/' . $filename);
          Image::make($file->getRealPath())->resize(468, 249)->save($path); */

            // $filePath = ('app/public/thumbnails');
          //  $filePath = public_path('images/series/' . $filename);
            $filePath =('images/series/' . $filename);
           // $img = Image::make($file->path());
            $img = Image::make($file->getRealPath());
            $img->resize(110, 110, function ($const) {
                $const->aspectRatio();
            })->save($filePath);
            //cropped images is going here 
           $cropped = ($filePath);
                 //orignal image is going here
            $path = $request->file('logo')->move('app/public/images',$fileName); 
            

            $Series = Series::create([
                    'title' => $request->title,
                    'description' => $request->description,
                    'logo'=>$cropped,
                ]);
               return ['message'=>'Series Added successfully web'];
        }
        else {

            return ['message'=>'I am in else condition'];
        }


        }

        



      

    

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Series  $series
     * @return \Illuminate\Http\Response
     */
    public function show(Series $series)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Series  $series
     * @return \Illuminate\Http\Response
     */
    public function edit(Series $series)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Series  $series
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $series =Series::findOrFail($id);
     //   dd($series);
        $image =$request->logo;
      
         dd($image);

     if($request->hasFile('logo'))  {
         
       dd('I am here in the if conditon from web');
        $this->validate($request, [ 
            'title' => ['required', 'string', 'max:255'],
            'description' => 'required|string',
              'logo' => 'required|image|mimes:jpg,png,jpeg,gif,svg|max:2048',
        ]);

         $file = $request->logo;
        // $fileName = time() . '.'.$file->getClientOriginalExtension();
    
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
         $path = $request->file('logo')->move('app/public/images',$fileName); 
         

        
             $Series = Series::where('id', $id)
                ->update([
                    'title' => $request->title,
                    'description' => $request->description,
                    'logo'=>$cropped,
                    ]);  

            return ['message'=>'Series Added successfully'];
     }
     else {

       dd('I am here in the else conditon from web');
        $this->validate($request, [ 
            'title' => ['required', 'string', 'max:255'],
            'description' => 'required|string',
            //  'logo' => 'required|image|mimes:jpg,png,jpeg,gif,svg|max:2048',
        ]);


        $Series = Series::where('id', $id)
        ->update([
            'title' => $request->title,
            'description' => $request->description,
           // 'logo'=>$cropped,
            ]);  
         return ['message'=>'I am in else condition'];
     }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Series  $series
     * @return \Illuminate\Http\Response
     */
    public function destroy(Series $series)
    {
        //
    }
}
