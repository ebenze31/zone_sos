@extends('layouts.theme_admin')

@section('content')
<div class="card border-top border-0 border-4 border-info">
	<div class="card-body">
		<div class="border p-4 rounded">
			<div class="card-title d-flex align-items-center">
				<div>
					<i class="fa-duotone fa-solid fa-handshake me-1 font-22 text-info"></i>
				</div>
				<h5 class="mb-0 text-info">Create Partner</h5>
			</div>
			<hr>
			<div class="row mb-3">
				<div class="col-3">
					<label for="" class="col-form-label">
						<b>ชื่อ (สำหรับแสดงผลในปุ่ม SOS)</b>
					</label>
					<input type="text" class="form-control" id="" name="" placeholder="">
				</div>
				<div class="col-6">
					<label for="" class="col-form-label">
						<b>ชื่อเต็ม</b>
					</label>
					<input type="text" class="form-control" id="" name="" placeholder="">
				</div>
				<div class="col-3">
					<label for="" class="col-form-label">
						<b>ประเภท</b>
					</label>
					<select class="form-control" id="" name="">
						<option>เลือกประเภท</option>
					</select>
				</div>
			</div>
			<div class="row mb-3">
				<div class="col-4">
					<label for="" class="col-form-label">
						<b>เบอร์ติดต่อ</b>
					</label>
					<input type="text" class="form-control" id="" name="" placeholder="">
				</div>
				<div class="col-4">
					<label for="" class="col-form-label">
						<b>E-Mail</b>
					</label>
					<input type="email" class="form-control" id="" name="" placeholder="">
				</div>
				<div class="col-4">
					<label for="" class="col-form-label">
						<b>LOGO</b> <span class="text-danger">*ขนาด 1080*1080</span>
					</label>
					<input type="file" class="form-control" id="" name="" placeholder="">
				</div>
			</div>
			<div class="row mb-3">
				<div class="col-4">
					<label for="" class="col-form-label">
						<b>Color CI 1</b>
					</label>
					<input type="text" class="form-control" id="" name="" placeholder="">
				</div>
				<div class="col-4">
					<label for="" class="col-form-label">
						<b>Color CI 2</b>
					</label>
					<input type="text" class="form-control" id="" name="" placeholder="">
				</div>
				<div class="col-4">
					<label for="" class="col-form-label">
						<b>Color CI 3</b>
					</label>
					<input type="text" class="form-control" id="" name="" placeholder="">
				</div>
			</div>
			<div class="row mb-3">
				<div class="col-3">
					<label for="" class="col-form-label">
						<b>จังหวัด</b>
					</label>
					<select class="form-control" id="" name="">
						<option>เลือกจังหวัด</option>
					</select>
				</div>
				<div class="col-3">
					<label for="" class="col-form-label">
						<b>อำเภอ</b>
					</label>
					<select class="form-control" id="" name="">
						<option>เลือกอำเภอ</option>
					</select>
				</div>
				<div class="col-3">
					<label for="" class="col-form-label">
						<b>ตำบล</b>
					</label>
					<select class="form-control" id="" name="">
						<option>เลือกตำบล</option>
					</select>
				</div>
				<div class="col-3">
					<label for="" class="col-form-label">
						<b>พื้นที่</b> <span class="text-danger">(ถ้ามี)</span>
					</label>
					<input type="text" class="form-control" id="" name="" placeholder="">
				</div>
			</div>
			<div class="row mb-3">
				<div class="col-2">
					<label for="" class="col-form-label">
						<b>Show Homepage</b>
					</label>
					<input class="mx-3 mt-2" type="checkbox" class="" id="" name="" placeholder="">
				</div>
				<div class="col-10">
					<label for="" class="col-form-label">
						<b>จำนวน Polygon สูงสุด</b>
					</label>
					<input type="number" min="1" class="form-control" id="" name="" value="1" style="width: 30%;">
				</div>
			</div>
			<div class="row mt-5">
				<div class="col-12">
					<button type="submit" class="btn btn-info px-5">
						Create Partner
					</button>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection
