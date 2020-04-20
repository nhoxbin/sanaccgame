@extends('layouts.auth')

@section('content')
	<div class="col-md-9 col-md-offset-2">
		<div class="content-main">
			<div class="content-reg">
				<div class="row" id="vw_resetpass">
					<div class="col-md-11 col-md-push-1">
						<div class="ht-reg">
							<div class="content-qmk">
								<div class="row">
									<div class="col-md-9 mg-sm-20">
										<div class="row">
											<div class="col-md-3 col-sm-2 col-xs-3"><img class="img-responsive algin-sm" src="{{ asset('web/client/images/icon-qmk1.png') }}" alt=""></div>
											<div class="col-md-9 col-sm-9 col-xs-8">
											Lấy lại mật khẩu <br> Qua SMS <i class="fa fa-angle-right"></i>
											</div>
										</div>
										<br>
										<h6 style="text-align:left">
											Vui lòng soạn tin<br />
											<b>ON SANACCGAME MK</b>&nbsp;gửi&nbsp;<b>8185 </b> <span>(1.000VNĐ/SMS)</span>
											<br />
											<span>Trong đó MK là mật khẩu muốn đổi</span>
										</h6>
									</div> 
								</div>
							</div>
						</div>
					</div> 
				</div>
			</div>
		</div> 
	</div>
@endsection