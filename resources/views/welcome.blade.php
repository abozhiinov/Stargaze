@extends('layouts.app')

@section('content')
<div class="container homepage">
	<div id="homepage_search-box" class="d-grid p-5 homepage_search-box">
		<form method="">
			<div id="homepage_search-row" class="row justify-content-around align-items-center">
				<input type="text" class="col-sm-5 mb-4" placeholder="Търси изпълнител...">
				<input type="text" class="col-sm-5 mb-4" placeholder="Търси място...">
				<div class="w-100"></div>
				<input type="text" onfocus="(this.type='date')" class="col-sm-5" placeholder="Търси по дата...">
				<select class="col-sm-5">
					<option value="" disabled selected>Търси по жанр...</option>
				</select>
				<input type="submit" value="Търси" class="button-custom w-50 mt-4">
			</div>
		</form>
	</div>
</div>
@endsection
