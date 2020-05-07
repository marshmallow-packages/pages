<div class="row">
	@foreach($layout->items as $usp)
    <div class="col-md-4 mb-7">
      <div class="text-center px-lg-3">
        <span class="btn btn-icon btn-lg btn-soft-danger rounded-circle mb-5">
          <span class="{!! $usp->icon !!} fa-2x btn-icon__inner btn-icon__inner-bottom-minus"></span>
        </span>
        <h3 class="h5">{{ $usp->title }}</h3>
        {!! $usp->content !!}
      </div>
    </div>
    @endforeach
  </div>