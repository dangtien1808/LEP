@if(isset($items))
    @foreach ($items as $item)
    <div class="col-1 mb-3">
        <div class="card">
            <img src="{{asset($item->image_path)}}" class="card-img-top img-thumbnail" alt="{{$item->name}}" style="height: 140px">
            <div class="card-body text-center p-2">
            <input type="button" value="Delete" class="btn btn-danger btn-sm delete-images" data-id="{{$item->id}}">
            </div>
        </div>
    </div>
    @endforeach
@endif