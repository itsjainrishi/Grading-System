@extends('Students.layouts.app')

@section('content')
<div class="container" style="margin-top: 2%;">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading" style="background-color: #3cb371; color:#fff" >Upload Excel</div>
                	<div class="panel-body">

					<form role="form" enctype="multipart/form-data" method="POST" action="{{ route('upload.marks', array( 'id' => $id)) }}">
					{{ csrf_field() }}
						<div class=form-group>
							<input type="file" name="marks"></input>
							<br>
						</div>
					
					<div class="form-group">
						<input type="submit" class=btn-primary style="background-color: #3cb371;color: #fff"></input>
					</div>

				</form>
				</div>
			</div>
		</div>
	</div>
</div>
</div>
@endsection