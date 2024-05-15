@extends('layouts.front-end.app')

@section('title',translate('Rider Registration'))

@push('css_or_js')

@endpush

@section('content')
<!-- Page Title-->
<div class="container rtl">
    <h3 class="py-3 m-0 text-center headerTitle">{{translate('Rider Registration')}}</h3>
</div>
<!-- Page Content-->
<div class="container pb-5 mb-2 mb-md-4 rtl">
    <div class="row">
        <!-- Content  -->
        <section class="col-lg-10 offset-md-1 col-md-10">
            <div class="card box-shadow-sm">
                <div class="card-header">
                    <form class="mt-3 px-sm-2 pb-2" action="{{route('rider-store')}}" method="post"
                        enctype="multipart/form-data">
                        <div class="row photoHeader g-3">
                            @csrf

                            <div class="card-body mt-md-3 p-0">
                                <h3 class="font-nameA">{{translate('account_information')}} </h3>

                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="title-color d-flex"
                                                for="f_name">{{translate('first_name')}}</label>
                                            <input type="text" name="f_name" value="{{old('f_name')}}"
                                                class="form-control" placeholder="{{translate('first_name')}}" required>
                                        </div>
                                    </div>
                                    {{-- <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="title-color d-flex"
                                                for="exampleFormControlInput1">{{translate('Middle')}}
                                                {{translate('name')}}</label>
                                            <input value="{{old('m_name')}}" type="text" name="m_name"
                                                class="form-control"
                                                placeholder="{{translate('Middle')}} {{translate('name')}}">
                                        </div>
                                    </div> --}}
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="title-color d-flex"
                                                for="exampleFormControlInput1">{{translate('last')}}
                                                {{translate('name')}}</label>
                                            <input value="{{old('l_name')}}" type="text" name="l_name"
                                                class="form-control"
                                                placeholder="{{translate('last')}} {{translate('name')}}">
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="title-color d-flex"
                                                for="exampleFormControlInput1">{{translate('Country Code')}}</label>
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <select
                                                        class="js-example-basic-multiple js-states js-example-responsive form-control"
                                                        name="country_code" required>
                                                        @foreach ($telephone_codes as $code)
                                                        <option value="{{ $code['code'] }}"
                                                            {{old($code['code'])==$code['code']? 'selected' : '' }}>{{
                                                            $code['name'] }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="title-color d-flex"
                                                for="exampleFormControlInput1">{{translate('phone')}}</label>
                                            <input value="{{old('phone')}}" type="text" name="phone"
                                                class="form-control" placeholder="{{translate('Ex : 017********')}}"
                                                required>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="title-color d-flex"
                                                for="exampleFormControlInput1">{{translate('address')}}</label>
                                            <div class="input-group mb-3">
                                                <textarea name="address" class="form-control" id="address" rows="1"
                                                    placeholder="{{translate('address')}}">{{ old('address') }}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="title-color d-flex"
                                                for="exampleFormControlInput1">{{translate('ABN')}}
                                                {{translate('number')}}</label>
                                            <input value="{{ old('abn_number') }}" type="text" name="abn_number"
                                                class="form-control" placeholder="{{translate('Ex : DH-23434-LS')}}"
                                                required>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="title-color d-flex"
                                                for="exampleFormControlInput1">{{translate('identity')}}
                                                {{translate('type')}}</label>
                                            <select name="identity_type" class="form-control">
                                                <option value="passport">{{translate('Australian')}}
                                                    {{translate('passport')}}</option>
                                                <option value="work_right">{{translate('Work')}} {{translate('right')}}
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="title-color d-flex"
                                                for="exampleFormControlInput1">{{translate('identity')}}
                                                {{translate('number')}}</label>
                                            <input value="{{ old('identity_number') }}" type="text"
                                                name="identity_number" class="form-control"
                                                placeholder="{{translate('Ex : DH-23434-LS')}}" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="title-color"
                                                for="exampleFormControlInput1">{{translate('identity')}}
                                                {{translate('image')}}</label>
                                            <div>
                                                <div class="row" id="coba"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="title-color">{{translate('Passport size personal
                                                photo')}}</label>
                                            <span class="text-info">* ( {{translate('ratio')}} 1:1 )</span>
                                            <div class="custom-file">
                                                <input value="{{ old('image') }}" type="file" name="image"
                                                    id="customFileEg1" class="custom-file-input"
                                                    accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*"
                                                    required>
                                                <label class="custom-file-label"
                                                    for="customFileEg1">{{translate('choose')}}
                                                    {{translate('file')}}</label>
                                            </div>
                                            <div class="row mt-4">
                                                <img class="upload-img-view col-md-4 previewHere" id="viewer"
                                                    src="{{asset('public\assets\back-end\img\400x400\img2.jpg')}}"
                                                    alt="delivery-man image" />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="title-color">{{translate('Driver Licence')}}</label>
                                            <span class="text-info">* ( {{translate('Front')}} )</span>
                                            <div class="custom-file">
                                                <input value="{{ old('driver_licence_front') }}" type="file"
                                                    name="driver_licence_front" id="driver_licence_front"
                                                    class="custom-file-input"
                                                    accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*"
                                                    required>
                                                <label class="custom-file-label"
                                                    for="driver_licence_front">{{translate('choose')}}
                                                    {{translate('file')}}</label>
                                            </div>
                                            <div class="row mt-4">
                                                <img class="upload-img-view col-md-4 previewHere"
                                                    id="driver_licence_front_preview"
                                                    src="{{asset('public\assets\back-end\img\400x400\img2.jpg')}}"
                                                    alt="driver_licence_front image" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="title-color">{{translate('Driver Licence')}}</label>
                                            <span class="text-info">* ( {{translate('Back')}} )</span>
                                            <div class="custom-file">
                                                <input value="{{ old('driver_licence_back') }}" type="file"
                                                    name="driver_licence_back" id="driver_licence_back"
                                                    class="custom-file-input"
                                                    accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*"
                                                    required>
                                                <label class="custom-file-label"
                                                    for="driver_licence_back">{{translate('choose')}}
                                                    {{translate('file')}}</label>
                                            </div>
                                            <div class="row mt-4">
                                                <img class="upload-img-view col-md-4 previewHere"
                                                    id="driver_licence_back_preview"
                                                    src="{{asset('public\assets\back-end\img\400x400\img2.jpg')}}"
                                                    alt="driver_licence_back image" />
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="title-color d-flex"
                                                for="exampleFormControlInput1">{{translate('email')}}</label>
                                            <input value="{{old('email')}}" type="email" name="email"
                                                class="form-control" placeholder="{{translate('Ex : ex@example.com')}}"
                                                required>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="title-color d-flex"
                                                for="exampleFormControlInput1">{{translate('password')}}</label>
                                            <input type="text" name="password" class="form-control"
                                                placeholder="{{translate('password_minimum_8_characters')}}" required>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="title-color d-flex"
                                                for="exampleFormControlInput1">{{translate('confirm_password')}}</label>
                                            <input type="text" name="confirm_password" class="form-control"
                                                placeholder="{{translate('password_minimum_8_characters')}}" required>
                                        </div>
                                    </div>
                                </div>

                                <div class="d-flex gap-3">
                                    <button type="submit" class="btn btn--primary px-4">{{translate('submit')}}</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </section>
    </div>
</div>
@endsection

@push('script')
<script>
    $(".js-example-responsive").select2({
            width: 'resolve'
        });
</script>
<script>
    function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    $(input).parent().siblings().find('.previewHere').attr('src', e.target.result);
                }

                reader.readAsDataURL(input.files[0]);
            }
        }

        $(".custom-file-input").change(function () {
            readURL(this);
        });
</script>

<script src="{{asset('public/assets/back-end/js/spartan-multi-image-picker.js')}}"></script>
<script type="text/javascript">
    $(function () {
            $("#coba").spartanMultiImagePicker({
                fieldName: 'identity_image[]',
                maxCount: 5,
                rowHeight: 'auto',
                groupClassName: 'col-6 col-lg-4',
                maxFileSize: '',
                placeholderImage: {
                    image: '{{asset('public/assets/back-end/img/400x400/img2.jpg')}}',
                    width: '100%'
                },
                dropFileLabel: "Drop Here",
                onAddRow: function (index, file) {

                },
                onRenderedPreview: function (index) {

                },
                onRemoveRow: function (index) {

                },
                onExtensionErr: function (index, file) {
                    toastr.error('Please only input png or jpg type file', {
                        CloseButton: true,
                        ProgressBar: true
                    });
                },
                onSizeErr: function (index, file) {
                    toastr.error('File size too big', {
                        CloseButton: true,
                        ProgressBar: true
                    });
                }
            });
        });
</script>
@endpush
