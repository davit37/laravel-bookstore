@extends('layouts.global')

@section('title') Create Category @endsection 

@section('content')

<div class="col-md-8">
  <form 
    enctype="multipart/form-data" 
    class="bg-white shadow-sm p-3" 
    action="{{route('categories.update', ['id'=>$categories->id])}}" 
    method="POST">

    @csrf

    @if(session('status'))
        <div class="alert alert-success">
            {{session('status')}}
        </div>
    @endif

    <input type="hidden" value="PUT" name="_method">

    <label>Category name</label><br>
    <input 
      type="text" 
      class="form-control" 
      name="name"
      value='{{$categories->name}}'/>
    <br>

    <label>Slug</label><br>
    <input 
      type="text" 
      class="form-control" 
      name="slug"
      value='{{$categories->slug}}'/>
    <br>

    <label>Category image</label>
    <br>
    Current image: <br>
        @if($categories->image)
        <img 
            src="{{asset('storage/'.$categories->image)}}" 
            width="120px" />
        <br>
        @else 
        No avatar
        @endif
        <br>
    <input 
      type="file" 
      class="form-control"
      name="image"/>
      <small class="text-muted">Kosongkan jika tidak ingin mengubah gambar</small>
      <br>

    <br>

    <input 
      type="submit" 
      class="btn btn-primary" 
      value="Save"/>

  </form>
</div>

@endsection