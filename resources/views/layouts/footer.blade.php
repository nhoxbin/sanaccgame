<div class="sl-footer">
    <div class="container">
        <div class="row">
            {{-- <div class="col-xs-12 col-sm-12 col-md-6">
                <div class="sa-fthotline">
                    <div class="sa-ftarow clearfix">
                        <div class="sa-ftacol sa-fthnum">
                            <p><a href="https://shopgamedz.com" target="_blank" style="color: #ffffff">[SHOPGAMEDZ] Nạp Game Mobile Giá Rẻ</a></p>
                            <p><a href="https://accdotkich.com/" target="_blank" title=" Nguyễn Cảnh Toàn"> </a></p>
                        </div>
                        <div class="sa-ftacol sa-fthwork">
                            Văn phòng giao dịch: Biên Hòa - Tp. Hồ Chí Minh
                        </div>
                    </div>
                </div>
            </div> --}}
            <div class="col-xs-12 col-sm-12 col-md-6 col-md-offset-6">
                <ul class="sl-ftviews">
                    <li>
                        <span class="sl-fti1"><img src="{{ asset('web/client/images/b1.png') }}"></span>
                        <p><strong>{{ $dashboard['number_account_done'] }} </strong> Acc đã bán</p>
                    </li>
                    <li>
                    <span class="sl-fti2"><img src="{{ asset('web/client/images/b2.png') }}"></span> 
                        <p><strong>{{ $dashboard['number_user'] }} </strong> Thành viên</p>
                    </li>
                    <li>
                        <span class="sl-fti3"><img src="{{ asset('web/client/images/b3.png') }}"></span> 
                        <p><strong>{{ $dashboard['number_account_selling'] }} </strong> Đang bán</p>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>