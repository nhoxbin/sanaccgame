@extends('layouts.app')
@section('content')
	<div class="container">
		<div id="loading-icon" style="display: none;">
	        <img src="{{ asset('web/client/images/loading.gif') }}">
	    </div>

		<div class="row justify-content-center">
	        <div class="col-md-8">
	            <div class="card">
	                <div class="card-header">{{ __('Chọn 1 trong 3 hình thức nạp tiền') }}</div>

	                <div class="card-body">
	                	<ul class="nav nav-tabs">
							<li class="nav-item">
								<a class="nav-link active" data-toggle="tab" href="#card">Nạp thẻ</a>
							</li>
							<li class="nav-item">
								<a class="nav-link" data-toggle="tab" href="#momo">Nạp momo</a>
							</li>
							<li class="nav-item">
								<a class="nav-link" data-toggle="tab" href="#nganluong">Ngân Hàng, Visa, Master Card</a>
							</li>
						</ul>

						<div class="tab-content">
							<div id="card" class="tab-pane fade active show"><br />
								<form method="POST" action="{{ route('recharge.store') }}" id="card-form">
			                        @csrf
			                        <input type="hidden" name="type" class="type" value="card">
			                        <div class="form-group">
			                        	<label for="" class="control-label float-left">Chọn mệnh giá</label>
				                        <select name="money" class="form-control">
											<option value="10K">10K</option>
                                            <option value="20K">20K</option>
                                            <option value="50K">50K</option>
											<option value="100K">100K</option>
											<option value="200K">200K</option>
                                            <option value="300K">300K</option>
											<option value="500K">500K</option>
										</select>
			                        </div>
			                        <div class="form-group">
			                        	<label for="" class="control-label float-left">Chọn nhà mạng</label>
                                        <select name="sim_id" id="sim" class="form-control">
                                            @foreach($sims as $sim)
                                                <option value="{{ $sim['id'] }}">{{ $sim['name'] . ' - ' . $sim['discount']}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group">
                                    	<label for="" class="control-label float-left">Số seri</label>
                                        <input type="number" class="form-control" name="serial" placeholder="Số serial number" value="{{ old('serial') }}">
                                    </div>
                                    <div class="form-group">
                                    	<label for="" class="control-label float-left">Mã nạp</label>
                                        <input type="number" class="form-control" name="code" placeholder="Mã nạp" value="{{ old('code') }}">
                                    </div>
			                    </form>
			                    <div class="col-xs-12">
                                    <h4><b>Lưu ý:</b> Mệnh giá bạn chọn ko đúng với mệnh giá thẻ, hệ thống sẽ <b>NUỐT THẺ</b> và ko hoàn trả lại, hãy cẩn thận</h4>
                                </div>
							</div>

							<div id="momo" class="tab-pane fade"><br />
								<p>Chuyển tiền vào tài khoản momo dưới đây và điền thông tin bên dưới!</p>
                                <img src="{{ asset('web/client/images/momo.png') }}" alt="momo payment" class="img img-responsive center">
								<form method="POST" action="{{ route('recharge.store') }}" id="momo-form">
			                        @csrf

			                        <input type="hidden" name="type" class="type" value="momo">
			                        <div class="form-group">
                                        <label for="" class="control-label float-left">SĐT người gửi</label>
                                        <input type="number" placeholder="090..." class="form-control" name="phone">
                                    </div>
                                    <div class="form-group">
                                        <label for="" class="control-label float-left">Mã giao dịch MoMo</label>
                                        <input placeholder="Mã giao dịch MoMo" class="form-control" type="number" name="code_momo">
                                    </div>
                                    <div class="form-group">
                                        <label for="" class="control-label float-left">Số tiền nạp</label>
                                        <input placeholder="Số tiền nạp" class="form-control" type="number" name="money">
                                    </div>
			                    </form>
							</div>

							<div id="nganluong" class="tab-pane fade"><br />
                                <span>Cảnh báo: khi nạp tiền bằng Ngân Lượng, bạn chỉ được phép nạp ngay, KHÔNG được phép tạm giữ. Số tiền sẽ không được cộng vào tài khoản nếu bạn ấn tạm giữ!!! Xin lưu ý.</span>
								<form action="{{ route('recharge.order') }}" method="post" autocomplete="off" id="nganluong-form">
                                    @csrf
                                    <div class="form-group">
                                        <input autocomplete="off" class="form-control valid" type="number" required name="total_amount" aria-invalid="false" placeholder="Số tiền nạp">
                                    </div>
                                    <div class="form-group">
                                        <input autocomplete="off" class="form-control valid" type="text" required name="buyer_fullname" aria-invalid="false" placeholder="Họ Tên">
                                    </div>
                                    <div class="form-group">
                                        <input autocomplete="off" class="form-control valid" type="email" required name="buyer_email" aria-invalid="false" placeholder="Email">
                                    </div>
                                    <div class="form-group">
                                        <input autocomplete="off" class="form-control valid" type="number" required name="buyer_mobile" aria-invalid="false" placeholder="Số điện thoại">
                                    </div>

                                    <ul class="list-content">
                                        <li class="active">
                                            <label><input type="radio" value="ATM_ONLINE" name="option_payment" checked>Thanh toán online bằng thẻ ngân hàng nội địa</label>
                                            <div class="boxContent">
                                                <p><i><span style="color:#ff5a00;font-weight:bold;text-decoration:underline;">Lưu ý</span>: Bạn cần đăng ký Internet-Banking hoặc dịch vụ thanh toán trực tuyến tại ngân hàng trước khi thực hiện.</i></p>
                                                <ul class="cardList clearfix">
                                                    <li class="bank-online-methods ">
                                                        <label for="vcb_ck_on">
                                                            <i class="BIDV" title="Ngân hàng TMCP Đầu tư &amp; Phát triển Việt Nam"></i>
                                                            <input type="radio" value="BIDV" name="bankcode">
                                                        </label>
                                                    </li>
                                                    <li class="bank-online-methods">
                                                        <label for="vcb_ck_on">
                                                            <i class="VCB" title="Ngân hàng TMCP Ngoại Thương Việt Nam"></i>
                                                            <input type="radio" value="VCB" name="bankcode">
                                                        </label>
                                                    </li>
                                                    <li class="bank-online-methods ">
                                                        <label for="vnbc_ck_on">
                                                            <i class="DAB" title="Ngân hàng Đông Á"></i>
                                                            <input type="radio" value="DAB"  name="bankcode">
                                                        </label>
                                                    </li>
                                                    <li class="bank-online-methods ">
                                                        <label for="tcb_ck_on">
                                                            <i class="TCB" title="Ngân hàng Kỹ Thương"></i>
                                                            <input type="radio" value="TCB"  name="bankcode">
                                                        </label>
                                                    </li>
                                                    <li class="bank-online-methods ">
                                                        <label for="sml_atm_mb_ck_on">
                                                            <i class="MB" title="Ngân hàng Quân Đội"></i>
                                                            <input type="radio" value="MB"  name="bankcode" >
                                                        
                                                    </label></li>

                                                    <li class="bank-online-methods ">
                                                        <label for="sml_atm_vib_ck_on">
                                                            <i class="VIB" title="Ngân hàng Quốc tế"></i>
                                                            <input type="radio" value="VIB"  name="bankcode" >
                                                        
                                                    </label></li>

                                                    <li class="bank-online-methods ">
                                                        <label for="sml_atm_vtb_ck_on">
                                                            <i class="ICB" title="Ngân hàng Công Thương Việt Nam"></i>
                                                            <input type="radio" value="ICB"  name="bankcode" >
                                                        
                                                    </label></li>

                                                    <li class="bank-online-methods ">
                                                        <label for="sml_atm_exb_ck_on">
                                                            <i class="EXB" title="Ngân hàng Xuất Nhập Khẩu"></i>
                                                            <input type="radio" value="EXB"  name="bankcode" >
                                                        
                                                    </label></li>

                                                    <li class="bank-online-methods ">
                                                        <label for="sml_atm_acb_ck_on">
                                                            <i class="ACB" title="Ngân hàng Á Châu"></i>
                                                            <input type="radio" value="ACB"  name="bankcode" >
                                                        
                                                    </label></li>

                                                    <li class="bank-online-methods ">
                                                        <label for="sml_atm_hdb_ck_on">
                                                            <i class="HDB" title="Ngân hàng Phát triển Nhà TPHCM"></i>
                                                            <input type="radio" value="HDB"  name="bankcode" >
                                                        
                                                    </label></li>

                                                    <li class="bank-online-methods ">
                                                        <label for="sml_atm_msb_ck_on">
                                                            <i class="MSB" title="Ngân hàng Hàng Hải"></i>
                                                            <input type="radio" value="MSB"  name="bankcode" >
                                                        
                                                    </label></li>

                                                    <li class="bank-online-methods ">
                                                        <label for="sml_atm_nvb_ck_on">
                                                            <i class="NVB" title="Ngân hàng Nam Việt"></i>
                                                            <input type="radio" value="NVB"  name="bankcode" >
                                                        
                                                    </label></li>

                                                    <li class="bank-online-methods ">
                                                        <label for="sml_atm_vab_ck_on">
                                                            <i class="VAB" title="Ngân hàng Việt Á"></i>
                                                            <input type="radio" value="VAB"  name="bankcode" >
                                                        
                                                    </label></li>

                                                    <li class="bank-online-methods ">
                                                        <label for="sml_atm_vpb_ck_on">
                                                            <i class="VPB" title="Ngân Hàng Việt Nam Thịnh Vượng"></i>
                                                            <input type="radio" value="VPB"  name="bankcode" >
                                                        
                                                    </label></li>

                                                    <li class="bank-online-methods ">
                                                        <label for="sml_atm_scb_ck_on">
                                                            <i class="SCB" title="Ngân hàng Sài Gòn Thương tín"></i>
                                                            <input type="radio" value="SCB"  name="bankcode" >
                                                        
                                                    </label></li>

                                                    <li class="bank-online-methods ">
                                                        <label for="bnt_atm_pgb_ck_on">
                                                            <i class="PGB" title="Ngân hàng Xăng dầu Petrolimex"></i>
                                                            <input type="radio" value="PGB"  name="bankcode" >
                                                        
                                                    </label></li>

                                                    <li class="bank-online-methods ">
                                                        <label for="bnt_atm_gpb_ck_on">
                                                            <i class="GPB" title="Ngân hàng TMCP Dầu khí Toàn Cầu"></i>
                                                            <input type="radio" value="GPB"  name="bankcode" >
                                                        
                                                    </label></li>

                                                    <li class="bank-online-methods ">
                                                        <label for="bnt_atm_agb_ck_on">
                                                            <i class="AGB" title="Ngân hàng Nông nghiệp &amp; Phát triển nông thôn"></i>
                                                            <input type="radio" value="AGB"  name="bankcode" >
                                                        
                                                    </label></li>

                                                    <li class="bank-online-methods ">
                                                        <label for="bnt_atm_sgb_ck_on">
                                                            <i class="SGB" title="Ngân hàng Sài Gòn Công Thương"></i>
                                                            <input type="radio" value="SGB"  name="bankcode" >
                                                        
                                                    </label></li>   
                                                    <li class="bank-online-methods ">
                                                        <label for="sml_atm_bab_ck_on">
                                                            <i class="BAB" title="Ngân hàng Bắc Á"></i>
                                                            <input type="radio" value="BAB"  name="bankcode" >
                                                        
                                                    </label></li>
                                                    <li class="bank-online-methods ">
                                                        <label for="sml_atm_bab_ck_on">
                                                            <i class="TPB" title="Tền phong bank"></i>
                                                            <input type="radio" value="TPB"  name="bankcode" >
                                                        
                                                    </label></li>
                                                    <li class="bank-online-methods ">
                                                        <label for="sml_atm_bab_ck_on">
                                                            <i class="NAB" title="Ngân hàng Nam Á"></i>
                                                            <input type="radio" value="NAB"  name="bankcode" >
                                                        
                                                    </label></li>
                                                    <li class="bank-online-methods ">
                                                        <label for="sml_atm_bab_ck_on">
                                                            <i class="SHB" title="Ngân hàng TMCP Sài Gòn - Hà Nội (SHB)"></i>
                                                            <input type="radio" value="SHB"  name="bankcode" >
                                                        
                                                    </label></li>
                                                    <li class="bank-online-methods ">
                                                        <label for="sml_atm_bab_ck_on">
                                                            <i class="OJB" title="Ngân hàng TMCP Đại Dương (OceanBank)"></i>
                                                            <input type="radio" value="OJB"  name="bankcode" >
                                                        
                                                    </label></li>                        
                                                </ul>
                                            </div>
                                        </li>
                                        <li>
                                            <label><input type="radio" value="IB_ONLINE" name="option_payment">Thanh toán bằng IB</label>
                                            <div class="boxContent">
                                                <p><i><span style="color:#ff5a00;font-weight:bold;text-decoration:underline;">Lưu ý</span>: Bạn cần đăng ký Internet-Banking hoặc dịch vụ thanh toán trực tuyến tại ngân hàng trước khi thực hiện.</i></p>

                                                <ul class="cardList clearfix">
                                                    <li class="bank-online-methods ">
                                                        <label for="vcb_ck_on">
                                                            <i class="BIDV" title="Ngân hàng TMCP Đầu tư &amp; Phát triển Việt Nam"></i>
                                                            <input type="radio" value="BIDV"  name="bankcode">
                                                        </label>
                                                    </li>
                                                    <li class="bank-online-methods ">
                                                        <label for="vcb_ck_on">
                                                            <i class="VCB" title="Ngân hàng TMCP Ngoại Thương Việt Nam"></i>
                                                            <input type="radio" value="VCB"  name="bankcode">
                                                        </label>
                                                    </li>
                                                    <li class="bank-online-methods ">
                                                        <label for="vnbc_ck_on">
                                                            <i class="DAB" title="Ngân hàng Đông Á"></i>
                                                            <input type="radio" value="DAB"  name="bankcode">
                                                        </label>
                                                    </li>
                                                    <li class="bank-online-methods ">
                                                        <label for="tcb_ck_on">
                                                            <i class="TCB" title="Ngân hàng Kỹ Thương"></i>
                                                            <input type="radio" value="TCB"  name="bankcode">
                                                        </label>
                                                    </li>
                                                </ul>
                                            </div>
                                        </li>
                                        <li>
                                            <label><input type="radio" value="ATM_OFFLINE" name="option_payment">Thanh toán atm offline</label>
                                            <div class="boxContent">
                                                <ul class="cardList clearfix">
                                                    <li class="bank-online-methods ">
                                                        <label for="vcb_ck_on">
                                                            <i class="BIDV" title="Ngân hàng TMCP Đầu tư &amp; Phát triển Việt Nam"></i>
                                                            <input type="radio" value="BIDV"  name="bankcode">
                                                        </label>
                                                    </li>
                                                        
                                                    <li class="bank-online-methods ">
                                                        <label for="vcb_ck_on">
                                                            <i class="VCB" title="Ngân hàng TMCP Ngoại Thương Việt Nam"></i>
                                                            <input type="radio" value="VCB"  name="bankcode">
                                                        </label>
                                                    </li>

                                                    <li class="bank-online-methods ">
                                                        <label for="vnbc_ck_on">
                                                            <i class="DAB" title="Ngân hàng Đông Á"></i>
                                                            <input type="radio" value="DAB"  name="bankcode">
                                                        </label>
                                                    </li>

                                                    <li class="bank-online-methods ">
                                                        <label for="tcb_ck_on">
                                                            <i class="TCB" title="Ngân hàng Kỹ Thương"></i>
                                                            <input type="radio" value="TCB"  name="bankcode">
                                                        </label>
                                                    </li>

                                                    <li class="bank-online-methods ">
                                                        <label for="sml_atm_mb_ck_on">
                                                            <i class="MB" title="Ngân hàng Quân Đội"></i>
                                                            <input type="radio" value="MB"  name="bankcode">
                                                        </label>
                                                    </li>

                                                    <li class="bank-online-methods ">
                                                        <label for="sml_atm_vtb_ck_on">
                                                            <i class="ICB" title="Ngân hàng Công Thương Việt Nam"></i>
                                                            <input type="radio" value="ICB"  name="bankcode">
                                                        </label>
                                                    </li>

                                                    <li class="bank-online-methods ">
                                                        <label for="sml_atm_acb_ck_on">
                                                            <i class="ACB" title="Ngân hàng Á Châu"></i>
                                                            <input type="radio" value="ACB"  name="bankcode">
                                                        </label>
                                                    </li>

                                                    <li class="bank-online-methods ">
                                                        <label for="sml_atm_msb_ck_on">
                                                            <i class="MSB" title="Ngân hàng Hàng Hải"></i>
                                                            <input type="radio" value="MSB"  name="bankcode">
                                                        </label>
                                                    </li>

                                                    <li class="bank-online-methods ">
                                                        <label for="sml_atm_scb_ck_on">
                                                            <i class="SCB" title="Ngân hàng Sài Gòn Thương tín"></i>
                                                            <input type="radio" value="SCB"  name="bankcode">
                                                        </label>
                                                    </li>

                                                    <li class="bank-online-methods ">
                                                        <label for="bnt_atm_pgb_ck_on">
                                                            <i class="PGB" title="Ngân hàng Xăng dầu Petrolimex"></i>
                                                            <input type="radio" value="PGB"  name="bankcode">
                                                        </label>
                                                    </li>
                                                    
                                                    <li class="bank-online-methods ">
                                                        <label for="bnt_atm_agb_ck_on">
                                                            <i class="AGB" title="Ngân hàng Nông nghiệp &amp; Phát triển nông thôn"></i>
                                                            <input type="radio" value="AGB"  name="bankcode">
                                                        </label>
                                                    </li>

                                                    <li class="bank-online-methods ">
                                                        <label for="sml_atm_bab_ck_on">
                                                            <i class="SHB" title="Ngân hàng TMCP Sài Gòn - Hà Nội (SHB)"></i>
                                                            <input type="radio" value="SHB"  name="bankcode">
                                                        </label>
                                                    </li>
                                                </ul>
                                            </div>
                                        </li>
                                        <li>
                                            <label><input type="radio" value="NH_OFFLINE" name="option_payment">Thanh toán tại văn phòng ngân hàng</label>
                                            <div class="boxContent">
                                                <ul class="cardList clearfix">
                                                    <li class="bank-online-methods ">
                                                        <label for="vcb_ck_on">
                                                            <i class="BIDV" title="Ngân hàng TMCP Đầu tư &amp; Phát triển Việt Nam"></i>
                                                            <input type="radio" value="BIDV"  name="bankcode" >

                                                        </label></li>
                                                    <li class="bank-online-methods ">
                                                        <label for="vcb_ck_on">
                                                            <i class="VCB" title="Ngân hàng TMCP Ngoại Thương Việt Nam"></i>
                                                            <input type="radio" value="VCB"  name="bankcode" >

                                                        </label></li>

                                                    <li class="bank-online-methods ">
                                                        <label for="vnbc_ck_on">
                                                            <i class="DAB" title="Ngân hàng Đông Á"></i>
                                                            <input type="radio" value="DAB"  name="bankcode" >

                                                        </label></li>

                                                    <li class="bank-online-methods ">
                                                        <label for="tcb_ck_on">
                                                            <i class="TCB" title="Ngân hàng Kỹ Thương"></i>
                                                            <input type="radio" value="TCB"  name="bankcode" >

                                                        </label></li>

                                                    <li class="bank-online-methods ">
                                                        <label for="sml_atm_mb_ck_on">
                                                            <i class="MB" title="Ngân hàng Quân Đội"></i>
                                                            <input type="radio" value="MB"  name="bankcode" >

                                                        </label></li>

                                                    <li class="bank-online-methods ">
                                                        <label for="sml_atm_vib_ck_on">
                                                            <i class="VIB" title="Ngân hàng Quốc tế"></i>
                                                            <input type="radio" value="VIB"  name="bankcode" >

                                                        </label></li>

                                                    <li class="bank-online-methods ">
                                                        <label for="sml_atm_vtb_ck_on">
                                                            <i class="ICB" title="Ngân hàng Công Thương Việt Nam"></i>
                                                            <input type="radio" value="ICB"  name="bankcode" >

                                                        </label></li>

                                                    <li class="bank-online-methods ">
                                                        <label for="sml_atm_acb_ck_on">
                                                            <i class="ACB" title="Ngân hàng Á Châu"></i>
                                                            <input type="radio" value="ACB"  name="bankcode" >

                                                        </label></li>

                                                    <li class="bank-online-methods ">
                                                        <label for="sml_atm_msb_ck_on">
                                                            <i class="MSB" title="Ngân hàng Hàng Hải"></i>
                                                            <input type="radio" value="MSB"  name="bankcode" >

                                                        </label></li>

                                                    <li class="bank-online-methods ">
                                                        <label for="sml_atm_scb_ck_on">
                                                            <i class="SCB" title="Ngân hàng Sài Gòn Thương tín"></i>
                                                            <input type="radio" value="SCB"  name="bankcode" >

                                                        </label></li>



                                                    <li class="bank-online-methods ">
                                                        <label for="bnt_atm_pgb_ck_on">
                                                            <i class="PGB" title="Ngân hàng Xăng dầu Petrolimex"></i>
                                                            <input type="radio" value="PGB"  name="bankcode" >

                                                        </label></li>

                                                    <li class="bank-online-methods ">
                                                        <label for="bnt_atm_agb_ck_on">
                                                            <i class="AGB" title="Ngân hàng Nông nghiệp &amp; Phát triển nông thôn"></i>
                                                            <input type="radio" value="AGB"  name="bankcode" >

                                                        </label></li>
                                                    <li class="bank-online-methods ">
                                                        <label for="sml_atm_bab_ck_on">
                                                            <i class="TPB" title="Tền phong bank"></i>
                                                            <input type="radio" value="TPB"  name="bankcode" >

                                                        </label>
                                                    </li>
                                                </ul>
                                            </div>
                                        </li>
                                        <li>
                                            <label><input type="radio" value="VISA" name="option_payment" selected="true">Thanh toán bằng thẻ Visa hoặc MasterCard</label>
                                            <div class="boxContent">
                                                <p><span style="color:#ff5a00;font-weight:bold;text-decoration:underline;">Lưu ý</span>:Visa hoặc MasterCard.</p>
                                                <ul class="cardList clearfix">
                                                    <li class="bank-online-methods">
                                                        <label for="vcb_ck_on">Visa:<input type="radio" value="VISA" name="bankcode"></label>
                                                    </li>
                                                    <li class="bank-online-methods ">
                                                        <label for="vnbc_ck_on">Master:<input type="radio" value="MASTER" name="bankcode"></label>
                                                    </li>
                                                </ul>   
                                            </div>
                                        </li>
                                        <li>
                                            <label><input type="radio" value="CREDIT_CARD_PREPAID" name="option_payment" selected="true">Thanh toán bằng thẻ Visa hoặc MasterCard trả trước</label>
                                        </li>
                                    </ul>
                                </form>
							</div>

							<div class="text-center col-xs-12 input-block" id="recharge">
                                <button type="button" class="btn btn-success recharge-btn">Nạp tiền</button>
                            </div>
						</div>
	                </div>
	            </div>
	        </div>
	    </div>
	</div>
@endsection
@push('style')
	<style>
		.tab-content {
		    border-left: 1px solid #ddd;
		    border-right: 1px solid #ddd;
		    border-bottom: 1px solid #ddd;
		    border-radius: 0px 0px 5px 5px;
		    padding: 10px;
		}

		.nav-tabs {
		    margin-bottom: 0;
		}

		.list-content li {
            list-style: none outside none;
            margin: 0 0 10px;
        }
        
        .list-content li .boxContent {
            display: none;
            width: 636px;
            border: 1px solid #cccccc;
            padding: 10px;
        }
        .list-content li.active .boxContent {
            display: block;
        }
        .list-content li .boxContent ul {
            height:280px;
        }
        
        i.VISA, i.MASTE, i.AMREX, i.JCB, i.VCB, i.TCB, i.MB, i.VIB, i.ICB, i.EXB, i.ACB, i.HDB, i.MSB, i.NVB, i.DAB, i.SHB, i.OJB, i.SEA, i.TPB, i.PGB, i.BIDV, i.AGB, i.SCB, i.VPB, i.VAB, i.GPB, i.SGB,i.NAB,i.BAB 
        { width:80px; height:30px; display:block; background:url(https://www.nganluong.vn/webskins/skins/nganluong/checkout/version3/images/bank_logo.png) no-repeat;}
        i.MASTE { background-position:0px -31px}
        i.AMREX { background-position:0px -62px}
        i.JCB { background-position:0px -93px;}
        i.VCB { background-position:0px -124px;}
        i.TCB { background-position:0px -155px;}
        i.MB { background-position:0px -186px;}
        i.VIB { background-position:0px -217px;}
        i.ICB { background-position:0px -248px;}
        i.EXB { background-position:0px -279px;}
        i.ACB { background-position:0px -310px;}
        i.HDB { background-position:0px -341px;}
        i.MSB { background-position:0px -372px;}
        i.NVB { background-position:0px -403px;}
        i.DAB { background-position:0px -434px;}
        i.SHB { background-position:0px -465px;}
        i.OJB { background-position:0px -496px;}
        i.SEA { background-position:0px -527px;}
        i.TPB { background-position:0px -558px;}
        i.PGB { background-position:0px -589px;}
        i.BIDV { background-position:0px -620px;}
        i.AGB { background-position:0px -651px;}
        i.SCB { background-position:0px -682px;}
        i.VPB { background-position:0px -713px;}
        i.VAB { background-position:0px -744px;}
        i.GPB { background-position:0px -775px;}
        i.SGB { background-position:0px -806px;}
        i.NAB { background-position:0px -837px;}
        i.BAB { background-position:0px -868px;}
        
        ul.cardList li {
            cursor: pointer;
            float: left;
            margin-right: 0;
            padding: 5px 4px;
            text-align: center;
            width: 90px;
        }
	</style>
@endpush
@push('script')
	<script>
		@if(session('checkout_url'))
			location.href = '{{ session('checkout_url') }}';
		@endif
		@if(session('errors'))
			alert('{{ $errors->first() }}');
		@endif
		@if(session('success') || session('error'))
			alert('{{ session('success') ?? session('error') }}');
		@endif

		$(document).ready(function() {
	        $('input[name="option_payment"]').bind('click', function() {
	            $('.list-content li').removeClass('active');
	            $(this).parent().parent('li').addClass('active');
	        });

	        $('.recharge-btn').on('click', function() {
	            let form = $('.tab-pane.active').find('form');
	            form.submit();
	        });
	    });
	</script>
@endpush