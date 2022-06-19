<!DOCTYPE html>
<html lang="en">
<head>
  <title>Bootstrap Example</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js%22%3E"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
</head>
<body>

<div class="container">
  <h2>Edit</h2>
  <form method="post" action="{{url('posts/'.$post->id)}}" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    
 
    <div class="form-group">
        <label for="title">Title:</label>
        <input type="text" class="form-control" id="title" value="{{$post->title}}" name="title">
      </div>

     {{--   <div class="form-group">
        <label for="short_description">Short Description:</label>
        <input type="text" class="form-control" id="short_description" placeholder="Enter the short description" name="short_description">
      </div>

      <div class="form-group">
          <label for="content">Content:</label>
          <input type="text" class="form-control" id="content" placeholder="Enter the Co" name="drzava">
        </div>  --}}

        <label for="short_description">Short Description:</label>
        <textarea  class="form-control" id="short_description" name="short_description" rows="20" cols="20">{{$post->short_description}}</textarea>
        <label for="content">Content:</label>
        <textarea  class="form-control" id="content" name="content" rows="20" cols="20">{{$post->content}}</textarea>

        <div class="form-group">
          <label for="picture"> New picture(opt):</label>
          <input type="file" class="form-control" id="picture" placeholder="Attach the new picture" name="picture">
        </div>
 
    <button type="submit" class="btn btn-primary btn-lg">Save</button>
  </form>
</div>

</body>
</html>