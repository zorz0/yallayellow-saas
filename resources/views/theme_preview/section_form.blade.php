<div class="card-body">
@if (isset($json_data->section))
    {{ Form::open(['route' => ['home.page.setting'], 'method' => 'post', 'enctype' => 'multipart/form-data']) }}
    <input type="hidden" name="section_name" value="{{ $json_data->section_slug }}">
    <input type="hidden" name="theme_id" value="{{ $currentTheme }}">
    <input type="hidden" name="array[section_name]" value="{{ $json_data->section_name }}">
    <input type="hidden" name="array[section_slug]" value="{{ $json_data->section_slug }}">
    <input type="hidden" name="array[unique_section_slug]" value="{{ $json_data->unique_section_slug }}">
    <input type="hidden" name="array[section_enable]" value="{{ $json_data->section_enable }}">
    <input type="hidden" name="array[array_type]" value="{{ $json_data->array_type }}">
    <input type="hidden" name="array[loop_number]" value="{{ $json_data->loop_number ?? 1 }}" id="slider-loop-number">

    <div class="card">
        <div class="card-header d-flex justify-content-between">
            <div>
                <h5> {{ $json_data->section_name }} </h5>
            </div>
        </div>
        <div class="card-body">
            @if(isset($json_data->section->background_image))
            <div class="row">
                <div class="col-sm-12">
                    <div class="form-group">
                        <label class="form-label">{{ $json_data->section->background_image->lable }}</label>
                        <input type="hidden" name="array[section][background_image][slug]" class="form-control" value="{{ $json_data->section->background_image->slug ?? '' }}">
                        <input type="hidden" name="array[section][background_image][lable]" class="form-control" value="{{ $json_data->section->background_image->lable ?? '' }}">
                        <input type="hidden" name="array[section][background_image][type]" class="form-control" value="{{ $json_data->section->background_image->type ?? '' }}">
                        <input type="hidden" name="array[section][background_image][placeholder]" class="form-control" value="{{ $json_data->section->background_image->placeholder ?? '' }}">
                        <input type="hidden" name="array[section][background_image][image]" class="form-control" value="{{ $json_data->section->background_image->image ?? '' }}">
                        <input type="file" name="array[section][background_image][text]" class="form-control" value="{{ $json_data->section->background_image->text ?? '' }}"
                                placeholder="{{ $json_data->section->background_image->placeholder }}" id="{{ $json_data->section->background_image->slug }}" accept="*">

                        <img src="{{ asset($json_data->section->background_image->image) }}" style="width: 200px; height: 200px;" class="{{ $json_data->section->background_image->slug.'_preview' }}" accept="image/*">

                    </div>
                </div>
            </div>
            @endif
            
            @if(isset($json_data->section->background_image_second))
            <div class="row">
                <div class="col-sm-12">
                    <div class="form-group">
                        <label class="form-label">{{ $json_data->section->background_image_second->lable }}</label>
                        <input type="hidden" name="array[section][background_image_second][slug]" class="form-control" value="{{ $json_data->section->background_image_second->slug ?? '' }}">
                        <input type="hidden" name="array[section][background_image][lable]" class="form-control" value="{{ $json_data->section->background_image_second->lable ?? '' }}">
                        <input type="hidden" name="array[section][background_image_second][type]" class="form-control" value="{{ $json_data->section->background_image_second->type ?? '' }}">
                        <input type="hidden" name="array[section][background_image_second][placeholder]" class="form-control" value="{{ $json_data->section->background_image_second->placeholder ?? '' }}">
                        <input type="hidden" name="array[section][background_image_second][image]" class="form-control" value="{{ $json_data->section->background_image_second->image ?? '' }}">
                        <input type="file" name="array[section][background_image_second][text]" class="form-control" value="{{ $json_data->section->background_image_second->text ?? '' }}"
                                placeholder="{{ $json_data->section->background_image_second->placeholder }}" id="{{ $json_data->section->background_image_second->slug }}">

                        <img src="{{ asset($json_data->section->background_image_second->image) }}" style="width: 200px; height: 200px;" class="{{ $json_data->section->background_image_second->slug.'_preview' }}" accept="image/*">

                    </div>
                </div>
            </div>
            @endif

            @if (isset($json_data->section->service_title))
                <div class="row">
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label
                                class="form-label">{{ $json_data->section->service_title->lable }}</label>
                            <input type="hidden" name="array[section][service_title][slug]"
                                class="form-control"
                                value="{{ $json_data->section->service_title->slug ?? '' }}">
                            <input type="hidden" name="array[section][service_title][lable]"
                                class="form-control"
                                value="{{ $json_data->section->service_title->lable ?? '' }}">
                            <input type="hidden" name="array[section][service_title][type]"
                                class="form-control"
                                value="{{ $json_data->section->service_title->type ?? '' }}">
                            <input type="hidden" name="array[section][service_title][placeholder]"
                                class="form-control"
                                value="{{ $json_data->section->service_title->placeholder ?? '' }}">
                            <input type="hidden" name="array[section][service_title][image]"
                                class="form-control"
                                value="{{ $json_data->section->service_title->image ?? '' }}">
                            <input type="text" name="array[section][service_title][text]"
                                class="form-control"
                                value="{{ $json_data->section->service_title->text ?? '' }}"
                                placeholder="{{ $json_data->section->service_title->placeholder }}"
                                id="{{ $json_data->section->service_title->slug }}">
                        </div>
                    </div>
                </div>
            @endif
            @if (isset($json_data->section->service_sub_title))
                <div class="row">
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label
                                class="form-label">{{ $json_data->section->service_sub_title->lable }}</label>
                            <input type="hidden" name="array[section][service_sub_title][slug]"
                                class="form-control"
                                value="{{ $json_data->section->service_sub_title->slug ?? '' }}">
                            <input type="hidden" name="array[section][service_sub_title][lable]"
                                class="form-control"
                                value="{{ $json_data->section->service_sub_title->lable ?? '' }}">
                            <input type="hidden" name="array[section][service_sub_title][type]"
                                class="form-control"
                                value="{{ $json_data->section->service_sub_title->type ?? '' }}">
                            <input type="hidden" name="array[section][service_sub_title][placeholder]"
                                class="form-control"
                                value="{{ $json_data->section->service_sub_title->placeholder ?? '' }}">
                            <input type="hidden" name="array[section][service_sub_title][image]"
                                class="form-control"
                                value="{{ $json_data->section->service_sub_title->image ?? '' }}">
                            <input type="text" name="array[section][service_sub_title][text]"
                                class="form-control"
                                value="{{ $json_data->section->service_sub_title->text ?? '' }}"
                                placeholder="{{ $json_data->section->service_sub_title->placeholder }}"
                                id="{{ $json_data->section->service_sub_title->slug }}">
                        </div>
                    </div>
                </div>
            @endif
            @if (isset($json_data->section->service_button))
                <div class="row">
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label
                                class="form-label">{{ $json_data->section->service_button->lable }}</label>
                            <input type="hidden" name="array[section][service_button][slug]"
                                class="form-control"
                                value="{{ $json_data->section->service_button->slug ?? '' }}">
                            <input type="hidden" name="array[section][service_button][lable]"
                                class="form-control"
                                value="{{ $json_data->section->service_button->lable ?? '' }}">
                            <input type="hidden" name="array[section][service_button][type]"
                                class="form-control"
                                value="{{ $json_data->section->service_button->type ?? '' }}">
                            <input type="hidden" name="array[section][service_button][placeholder]"
                                class="form-control"
                                value="{{ $json_data->section->service_button->placeholder ?? '' }}">
                            <input type="hidden" name="array[section][service_button][image]"
                                class="form-control"
                                value="{{ $json_data->section->service_button->image ?? '' }}">
                            <input type="text" name="array[section][service_button][text]"
                                class="form-control"
                                value="{{ $json_data->section->service_button->text ?? '' }}"
                                placeholder="{{ $json_data->section->service_button->placeholder }}"
                                id="{{ $json_data->section->service_button->slug }}">
                        </div>
                    </div>
                </div>
            @endif
            @if (isset($json_data->section->service_description))
                <div class="row">
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label
                                class="form-label">{{ $json_data->section->service_description->lable }}</label>
                            <input type="hidden" name="array[section][service_description][slug]"
                                class="form-control"
                                value="{{ $json_data->section->service_description->slug ?? '' }}">
                            <input type="hidden" name="array[section][service_description][lable]"
                                class="form-control"
                                value="{{ $json_data->section->service_description->lable ?? '' }}">
                            <input type="hidden" name="array[section][service_description][type]"
                                class="form-control"
                                value="{{ $json_data->section->service_description->type ?? '' }}">
                            <input type="hidden" name="array[section][service_description][placeholder]"
                                class="form-control"
                                value="{{ $json_data->section->service_description->placeholder ?? '' }}">
                            <input type="hidden" name="array[section][service_description][image]"
                                class="form-control"
                                value="{{ $json_data->section->service_description->image ?? '' }}">
                            <textarea name="array[section][service_description][text]"
                                class="form-control"
                                placeholder="{{ $json_data->section->service_description->placeholder }}"
                                id="{{ $json_data->section->service_description->slug }}"> {{ $json_data->section->service_description->text ?? '' }} </textarea>
                        </div>
                    </div>
                </div>
            @endif
            @if (isset($json_data->section->slider_sub_description))
                <div class="col-sm-12">
                    <div class="form-group">
                        <label
                            class="form-label">{{ $json_data->section->slider_sub_description->lable }}</label>
                        <input type="hidden"
                            name="array[section][slider_sub_description][slug]"
                            class="form-control"
                            value="{{ $json_data->section->slider_sub_description->slug ?? '' }}">
                        <input type="hidden"
                            name="array[section][slider_sub_description][lable]"
                            class="form-control"
                            value="{{ $json_data->section->slider_sub_description->lable ?? '' }}">
                        <input type="hidden"
                            name="array[section][slider_sub_description][type]"
                            class="form-control"
                            value="{{ $json_data->section->slider_sub_description->type ?? '' }}">
                        <input type="hidden"
                            name="array[section][slider_sub_description][placeholder]"
                            class="form-control"
                            value="{{ $json_data->section->slider_sub_description->placeholder ?? '' }}">
                        <textarea type="text" name="array[section][slider_sub_description][text]" class="form-control"
                            placeholder="{{ $json_data->section->slider_sub_description->placeholder }}"
                            id="{{ $json_data->section->slider_sub_description->slug }}"> {{ $json_data->section->slider_sub_description->text ?? '' }} </textarea>

                    </div>
                </div>
            @endif
        </div>
        <div class="card-body slider-body-rows">
            @for($i=0; $i< $json_data->loop_number; $i++)
            <div class="row slider_{{$i}}" data-slider-index="{{$i}}">
                @if($json_data->array_type == 'multi-inner-list')
                    @if($json_data->section_name == 'Homepage Slider')
                        <div class="accordion accordion-flush" id="accordionFlushExample">
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="flush-headingOne{{$i}}">
                                    <button class="accordion-button collapsed slider-collspan" type="button" data-bs-toggle="collapse" data-bs-target="#{{$json_data->section_slug . '_'. $i}}" aria-expanded="false" aria-controls="{{$json_data->section_slug . '_'. $i}}">
                                        {{$json_data->section_name}}
                                    </button>
                                </h2>
                                <div id="{{$json_data->section_slug . '_'.$i}}" class="accordion-collapse collapse slider-collspan-body" aria-labelledby="flush-headingOne{{$i}}" data-bs-parent="#accordionFlushExample">
                                    <div class="accordion-body">

                                        @if(isset($json_data->section->title))
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label class="form-label">{{ $json_data->section->title->lable }}</label>
                                                <input type="hidden" name="array[section][title][slug]" class="form-control" value="{{ $json_data->section->title->slug ?? '' }}">
                                                <input type="hidden" name="array[section][title][lable]" class="form-control" value="{{ $json_data->section->title->lable ?? '' }}">
                                                <input type="hidden" name="array[section][title][type]" class="form-control" value="{{ $json_data->section->title->type ?? '' }}">
                                                <input type="hidden" name="array[section][title][placeholder]" class="form-control" value="{{ $json_data->section->title->placeholder ?? '' }}">
                                                <input type="text" name="array[section][title][text][{{$i}}]" class="form-control" value="{{ $json_data->section->title->text->{$i} ?? '' }}"
                                                        placeholder="{{ $json_data->section->title->placeholder }}" id="{{ $json_data->section->title->slug.'_'.$i }}">

                                            </div>
                                        </div>
                                        @endif
                                        @if(isset($json_data->section->sub_title))
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label class="form-label">{{ $json_data->section->sub_title->lable }}</label>
                                                <input type="hidden" name="array[section][sub_title][slug]" class="form-control" value="{{ $json_data->section->sub_title->slug ?? '' }}">
                                                <input type="hidden" name="array[section][sub_title][lable]" class="form-control" value="{{ $json_data->section->sub_title->lable ?? '' }}">
                                                <input type="hidden" name="array[section][sub_title][type]" class="form-control" value="{{ $json_data->section->sub_title->type ?? '' }}">
                                                <input type="hidden" name="array[section][sub_title][placeholder]" class="form-control" value="{{ $json_data->section->sub_title->placeholder ?? '' }}">
                                                <input type="text" name="array[section][sub_title][text][{{$i}}]" class="form-control" value="{{ $json_data->section->sub_title->text->{$i} ?? '' }}"
                                                        placeholder="{{ $json_data->section->sub_title->placeholder }}" id="{{ $json_data->section->sub_title->slug.'_'.$i }}">

                                            </div>
                                        </div>
                                        @endif
                                        @if(isset($json_data->section->button_first))
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label class="form-label">{{ $json_data->section->button_first->lable }}</label>
                                                <input type="hidden" name="array[section][button_first][slug]" class="form-control" value="{{ $json_data->section->button_first->slug ?? '' }}">
                                                <input type="hidden" name="array[section][button_first][lable]" class="form-control" value="{{ $json_data->section->button_first->lable ?? '' }}">
                                                <input type="hidden" name="array[section][button_first][type]" class="form-control" value="{{ $json_data->section->button_first->type ?? '' }}">
                                                <input type="hidden" name="array[section][button_first][placeholder]" class="form-control" value="{{ $json_data->section->button_first->placeholder ?? '' }}">
                                                <input type="text" name="array[section][button_first][text][{{$i}}]" class="form-control" value="{{ $json_data->section->button_first->text->{$i} ?? '' }}"
                                                        placeholder="{{ $json_data->section->button_first->placeholder }}" id="{{ $json_data->section->button_first->slug.'_'.$i }}">

                                            </div>
                                        </div>
                                        @endif
                                        
                                        @if(isset($json_data->section->button))
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label class="form-label">{{ $json_data->section->button->lable }}</label>
                                                <input type="hidden" name="array[section][button][slug]" class="form-control" value="{{ $json_data->section->button->slug ?? '' }}">
                                                <input type="hidden" name="array[section][button][lable]" class="form-control" value="{{ $json_data->section->button->lable ?? '' }}">
                                                <input type="hidden" name="array[section][button][type]" class="form-control" value="{{ $json_data->section->button->type ?? '' }}">
                                                <input type="hidden" name="array[section][button][placeholder]" class="form-control" value="{{ $json_data->section->button->placeholder ?? '' }}">
                                                <input type="text" name="array[section][button][text][{{$i}}]" class="form-control" value="{{ $json_data->section->button->text->{$i} ?? '' }}"
                                                        placeholder="{{ $json_data->section->button->placeholder }}" id="{{ $json_data->section->button->slug.'_'.$i }}">

                                            </div>
                                        </div>
                                        @endif

                                        @if(isset($json_data->section->button_second))
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label class="form-label">{{ $json_data->section->button_second->lable }}</label>
                                                <input type="hidden" name="array[section][button_second][slug]" class="form-control" value="{{ $json_data->section->button_second->slug ?? '' }}">
                                                <input type="hidden" name="array[section][button_second][lable]" class="form-control" value="{{ $json_data->section->button_second->lable ?? '' }}">
                                                <input type="hidden" name="array[section][button_second][type]" class="form-control" value="{{ $json_data->section->button_second->type ?? '' }}">
                                                <input type="hidden" name="array[section][button_second][placeholder]" class="form-control" value="{{ $json_data->section->button_second->placeholder ?? '' }}">
                                                <input type="text" name="array[section][button_second][text][{{$i}}]" class="form-control" value="{{ $json_data->section->button_second->text->{$i} ?? '' }}"
                                                        placeholder="{{ $json_data->section->button_second->placeholder }}" id="{{ $json_data->section->button_second->slug.'_'.$i }}">

                                            </div>
                                        </div>
                                        @endif
                                        @if(isset($json_data->section->description))
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label class="form-label">{{ $json_data->section->description->lable }}</label>
                                                <input type="hidden" name="array[section][description][slug]" class="form-control" value="{{ $json_data->section->description->slug ?? '' }}">
                                                <input type="hidden" name="array[section][description][lable]" class="form-control" value="{{ $json_data->section->description->lable ?? '' }}">
                                                <input type="hidden" name="array[section][description][type]" class="form-control" value="{{ $json_data->section->description->type ?? '' }}">
                                                <input type="hidden" name="array[section][description][placeholder]" class="form-control" value="{{ $json_data->section->description->placeholder ?? '' }}">
                                                <textarea type="text" name="array[section][description][text][{{$i}}]" class="form-control"
                                                        placeholder="{{ $json_data->section->description->placeholder }}" id="{{ $json_data->section->description->slug.'_'.$i }}"> {{ $json_data->section->description->text->{$i} ?? ''}} </textarea>

                                            </div>
                                        </div>
                                        @endif
                                        @if(isset($json_data->section->image))                                       
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label class="form-label">{{ $json_data->section->image->lable }}</label>
                                                <input type="hidden" name="array[section][image][slug]" class="form-control" value="{{ $json_data->section->image->slug ?? '' }}">
                                                <input type="hidden" name="array[section][image][lable]" class="form-control" value="{{ $json_data->section->image->lable ?? '' }}">
                                                <input type="hidden" name="array[section][image][type]" class="form-control" value="{{ $json_data->section->image->type ?? '' }}">
                                                <input type="hidden" name="array[section][image][placeholder]" class="form-control" value="{{ $json_data->section->image->placeholder ?? '' }}">
                                                <input type="hidden" name="array[section][image][image][{{$i}}]" class="form-control"
                                                value="{{ $json_data->section->image->image->{$i} ?? '' }}" >
                                                <input type="file" name="array[section][image][text][{{$i}}]" class="form-control"
                                                        placeholder="{{ $json_data->section->image->placeholder }}" >
                                                 <img src="{{ asset($json_data->section->image->image->{$i} ?? '') }}" id="{{ $json_data->section->image->slug.'_'.$i }}" accept="image/*">

                                            </div>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @else
                        @if(isset($json_data->section->title))
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label class="form-label">{{ $json_data->section->title->lable }}</label>
                                    <input type="hidden" name="array[section][title][slug]" class="form-control" value="{{ $json_data->section->title->slug ?? '' }}">
                                    <input type="hidden" name="array[section][title][lable]" class="form-control" value="{{ $json_data->section->title->lable ?? '' }}">
                                    <input type="hidden" name="array[section][title][type]" class="form-control" value="{{ $json_data->section->title->type ?? '' }}">
                                    <input type="hidden" name="array[section][title][placeholder]" class="form-control" value="{{ $json_data->section->title->placeholder ?? '' }}">
                                    <input type="text" name="array[section][title][text][{{$i}}]" class="form-control" value="{{ $json_data->section->title->text->{$i} ?? '' }}"
                                            placeholder="{{ $json_data->section->title->placeholder }}" id="{{ $json_data->section->title->slug.'_'.$i }}">

                                </div>
                            </div>
                        @endif
                        @if(isset($json_data->section->sub_title))
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label class="form-label">{{ $json_data->section->sub_title->lable }}</label>
                                <input type="hidden" name="array[section][sub_title][slug]" class="form-control" value="{{ $json_data->section->sub_title->slug ?? '' }}">
                                <input type="hidden" name="array[section][sub_title][lable]" class="form-control" value="{{ $json_data->section->sub_title->lable ?? '' }}">
                                <input type="hidden" name="array[section][sub_title][type]" class="form-control" value="{{ $json_data->section->sub_title->type ?? '' }}">
                                <input type="hidden" name="array[section][sub_title][placeholder]" class="form-control" value="{{ $json_data->section->sub_title->placeholder ?? '' }}">
                                <input type="text" name="array[section][sub_title][text][{{$i}}]" class="form-control" value="{{ $json_data->section->sub_title->text->{$i} ?? '' }}"
                                        placeholder="{{ $json_data->section->sub_title->placeholder }}" id="{{ $json_data->section->sub_title->slug.'_'.$i }}">

                            </div>
                        </div>
                        @endif
                        @if(isset($json_data->section->button))
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label class="form-label">{{ $json_data->section->button->lable }}</label>
                                <input type="hidden" name="array[section][button][slug]" class="form-control" value="{{ $json_data->section->button->slug ?? '' }}">
                                <input type="hidden" name="array[section][button][lable]" class="form-control" value="{{ $json_data->section->button->lable ?? '' }}">
                                <input type="hidden" name="array[section][button][type]" class="form-control" value="{{ $json_data->section->button->type ?? '' }}">
                                <input type="hidden" name="array[section][button][placeholder]" class="form-control" value="{{ $json_data->section->button->placeholder ?? '' }}">
                                <input type="text" name="array[section][button][text][{{$i}}]" class="form-control" value="{{ $json_data->section->button->text->{$i} ?? '' }}"
                                        placeholder="{{ $json_data->section->button->placeholder }}" id="{{ $json_data->section->button->slug.'_'.$i }}">

                            </div>
                        </div>
                        @endif
                        @if(isset($json_data->section->button_first))
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label class="form-label">{{ $json_data->section->button_first->lable }}</label>
                                <input type="hidden" name="array[section][button_first][slug]" class="form-control" value="{{ $json_data->section->button_first->slug ?? '' }}">
                                <input type="hidden" name="array[section][button_first][lable]" class="form-control" value="{{ $json_data->section->button_first->lable ?? '' }}">
                                <input type="hidden" name="array[section][button_first][type]" class="form-control" value="{{ $json_data->section->button_first->type ?? '' }}">
                                <input type="hidden" name="array[section][button_first][placeholder]" class="form-control" value="{{ $json_data->section->button_first->placeholder ?? '' }}">
                                <input type="text" name="array[section][button_first][text][{{$i}}]" class="form-control" value="{{ $json_data->section->button_first->text->{$i} ?? '' }}"
                                        placeholder="{{ $json_data->section->button_first->placeholder }}" id="{{ $json_data->section->button_first->slug.'_'.$i }}">

                            </div>
                        </div>
                        @endif
                        @if(isset($json_data->section->button_second))
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label class="form-label">{{ $json_data->section->button_second->lable }}</label>
                                <input type="hidden" name="array[section][button_second][slug]" class="form-control" value="{{ $json_data->section->button_second->slug ?? '' }}">
                                <input type="hidden" name="array[section][button_second][lable]" class="form-control" value="{{ $json_data->section->button_second->lable ?? '' }}">
                                <input type="hidden" name="array[section][button_second][type]" class="form-control" value="{{ $json_data->section->button_second->type ?? '' }}">
                                <input type="hidden" name="array[section][button_second][placeholder]" class="form-control" value="{{ $json_data->section->button_second->placeholder ?? '' }}">
                                <input type="text" name="array[section][button_second][text][{{$i}}]" class="form-control" value="{{ $json_data->section->button_second->text->{$i} ?? '' }}"
                                        placeholder="{{ $json_data->section->button_second->placeholder }}" id="{{ $json_data->section->button_second->slug.'_'.$i }}">

                            </div>
                        </div>
                        @endif
                        @if(isset($json_data->section->description))
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label class="form-label">{{ $json_data->section->description->lable }}</label>
                                <input type="hidden" name="array[section][description][slug]" class="form-control" value="{{ $json_data->section->description->slug ?? '' }}">
                                <input type="hidden" name="array[section][description][lable]" class="form-control" value="{{ $json_data->section->description->lable ?? '' }}">
                                <input type="hidden" name="array[section][description][type]" class="form-control" value="{{ $json_data->section->description->type ?? '' }}">
                                <input type="hidden" name="array[section][description][placeholder]" class="form-control" value="{{ $json_data->section->description->placeholder ?? '' }}">
                                <textarea type="text" name="array[section][description][text][{{$i}}]" class="form-control"
                                        placeholder="{{ $json_data->section->description->placeholder }}" id="{{ $json_data->section->description->slug.'_'.$i }}"> {{ $json_data->section->description->text->{$i} ?? '' }} </textarea>

                            </div>
                        </div>
                        @endif
                        <hr/>
                        @if(isset($json_data->section->image))                                       
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label class="form-label">{{ $json_data->section->image->lable }}</label>
                                <input type="hidden" name="array[section][image][slug]" class="form-control" value="{{ $json_data->section->image->slug ?? '' }}">
                                <input type="hidden" name="array[section][image][lable]" class="form-control" value="{{ $json_data->section->image->lable ?? '' }}">
                                <input type="hidden" name="array[section][image][type]" class="form-control" value="{{ $json_data->section->image->type ?? '' }}">
                                <input type="hidden" name="array[section][image][placeholder]" class="form-control" value="{{ $json_data->section->image->placeholder ?? '' }}">
                                <input type="hidden" name="array[section][image][image][{{$i}}]" class="form-control"
                                value="{{ $json_data->section->image->image->{$i} ?? '' }}" >
                                <input type="file" name="array[section][image][text][{{$i}}]" class="form-control"
                                        placeholder="{{ $json_data->section->image->placeholder }}" accept="*">
                                    <img src="{{ asset($json_data->section->image->image->{$i} ?? '') }}" id="{{ $json_data->section->image->slug.'_'.$i }}" accept="image/*">

                            </div>
                        </div>
                        @endif
                    @endif
                @else
                    @if (isset($json_data->section->title))
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label class="form-label">{{ $json_data->section->title->lable }}</label>
                            <input type="hidden" name="array[section][title][slug]" class="form-control" value="{{ $json_data->section->title->slug ?? '' }}">
                            <input type="hidden" name="array[section][title][lable]" class="form-control" value="{{ $json_data->section->title->lable ?? '' }}">
                            <input type="hidden" name="array[section][title][type]" class="form-control" value="{{ $json_data->section->title->type ?? '' }}">
                            <input type="hidden" name="array[section][title][placeholder]" class="form-control" value="{{ $json_data->section->title->placeholder ?? '' }}">
                            @if (isset($json_data->section->title->text) && (is_array($json_data->section->title->text) || is_object($json_data->section->title->text)) && isset($json_data->section->title->loop_number))
                                <hr/>   
                                @for($i=0; $i<$json_data->section->title->loop_number; $i++)
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label class="form-label">{{ $json_data->section->title->lable .' '. ($i+1) }}</label>
                                            <input type="text" name="array[section][title][text][{{ $i }}]" class="form-control" id="{{ ($json_data->section->title->lable ?? '').'_'.$i }}" value="{{ $json_data->section->title->text->{$i} ?? '' }}">
                                        </div>
                                    </div>
                                </div>
                                @endfor
                            @else
                            <input type="text" name="array[section][title][text]" class="form-control" value="{{ $json_data->section->title->text ?? '' }}"
                                    placeholder="{{ $json_data->section->title->placeholder }}" id="{{ $json_data->section->title->slug }}">
                            @endif
                        </div>
                    </div>
                    @endif
                    @if (isset($json_data->section->support_title))
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label class="form-label">{{ $json_data->section->support_title->lable }}</label>
                            <input type="hidden" name="array[section][support_title][slug]" class="form-control" value="{{ $json_data->section->support_title->slug ?? '' }}">
                            <input type="hidden" name="array[section][support_title][lable]" class="form-control" value="{{ $json_data->section->support_title->lable ?? '' }}">
                            <input type="hidden" name="array[section][support_title][type]" class="form-control" value="{{ $json_data->section->support_title->type ?? '' }}">
                            <input type="hidden" name="array[section][support_title][placeholder]" class="form-control" value="{{ $json_data->section->support_title->placeholder ?? '' }}">
                          

                            @if (isset($json_data->section->support_title->text) && (is_array($json_data->section->support_title->text) || is_object($json_data->section->support_title->text)) && isset($json_data->section->support_title->loop_number))
                                <hr/>   
                                @for($i=0; $i<$json_data->section->support_title->loop_number; $i++)
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label class="form-label">{{ $json_data->section->support_title->lable .' '. ($i+1) }}</label>
                                            <input type="text" name="array[section][support_title][text][{{ $i }}]" class="form-control" id="{{ ($json_data->section->support_title->lable ?? '').'_'.$i }}" value="{{ $json_data->section->support_title->text->{$i} ?? '' }}">
                                        </div>
                                    </div>
                                </div>
                                @endfor
                            @else
                            <input type="text" name="array[section][support_title][text]" class="form-control" value="{{ $json_data->section->support_title->text ?? '' }}"
                                    placeholder="{{ $json_data->section->support_title->placeholder }}" id="{{ $json_data->section->support_title->slug }}">
                            @endif

                        </div>
                    </div>
                    @endif
                    @if (isset($json_data->section->support_value))
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label class="form-label">{{ $json_data->section->support_value->lable }}</label>
                            <input type="hidden" name="array[section][support_value][slug]" class="form-control" value="{{ $json_data->section->support_value->slug ?? '' }}">
                            <input type="hidden" name="array[section][support_value][lable]" class="form-control" value="{{ $json_data->section->support_value->lable ?? '' }}">
                            <input type="hidden" name="array[section][support_value][type]" class="form-control" value="{{ $json_data->section->support_value->type ?? '' }}">
                            <input type="hidden" name="array[section][support_value][placeholder]" class="form-control" value="{{ $json_data->section->support_value->placeholder ?? '' }}">
                            
                                    @if (isset($json_data->section->support_value->text) && (is_array($json_data->section->support_value->text) || is_object($json_data->section->support_value->text)) && isset($json_data->section->support_value->loop_number))
                                <hr/>   
                                @for($i=0; $i<$json_data->section->support_value->loop_number; $i++)
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label class="form-label">{{ $json_data->section->support_value->lable .' '. ($i+1) }}</label>
                                            <input type="text" name="array[section][support_value][text][{{ $i }}]" class="form-control" id="{{ ($json_data->section->support_value->lable ?? '').'_'.$i }}" value="{{ $json_data->section->support_value->text->{$i} ?? '' }}">
                                        </div>
                                    </div>
                                </div>
                                @endfor
                            @else
                            <input type="text" name="array[section][support_value][text]" class="form-control" value="{{ $json_data->section->support_value->text ?? '' }}"
                                    placeholder="{{ $json_data->section->support_value->placeholder }}" id="{{ $json_data->section->support_value->slug }}">
                            @endif

                        </div>
                    </div>
                    @endif
                    @if(isset($json_data->section->sub_title))
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label class="form-label">{{ $json_data->section->sub_title->lable }}</label>
                            <input type="hidden" name="array[section][sub_title][slug]" class="form-control" value="{{ $json_data->section->sub_title->slug ?? '' }}">
                            <input type="hidden" name="array[section][sub_title][lable]" class="form-control" value="{{ $json_data->section->sub_title->lable ?? '' }}">
                            <input type="hidden" name="array[section][sub_title][type]" class="form-control" value="{{ $json_data->section->sub_title->type ?? '' }}">
                            <input type="hidden" name="array[section][sub_title][placeholder]" class="form-control" value="{{ $json_data->section->sub_title->placeholder ?? '' }}">
                            @if (isset($json_data->section->sub_title->text) && (is_array($json_data->section->sub_title->text) || is_object($json_data->section->sub_title->text)) && isset($json_data->section->sub_title->loop_number))
                                <hr/>   
                                @for($i=0; $i<$json_data->section->sub_title->loop_number; $i++)
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label class="form-label">{{ $json_data->section->sub_title->lable .' '. ($i+1) }}</label>
                                            <input type="text" name="array[section][sub_title][text][{{ $i }}]" class="form-control" id="{{ ($json_data->section->sub_title->lable ?? '').'_'.$i }}" value="{{ $json_data->section->sub_title->text->{$i} ?? '' }}">
                                        </div>
                                    </div>
                                </div>
                                @endfor
                            @else
                            <input type="text" name="array[section][sub_title][text]" class="form-control" value="{{ $json_data->section->sub_title->text ?? '' }}"
                                    placeholder="{{ $json_data->section->sub_title->placeholder }}" id="{{ $json_data->section->sub_title->slug }}">
                            @endif
                        </div>
                    </div>
                    @endif
                    @if(isset($json_data->section->lable_x))
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label class="form-label">{{ $json_data->section->lable_x->lable }}</label>
                            <input type="hidden" name="array[section][lable_x][slug]" class="form-control" value="{{ $json_data->section->lable_x->slug ?? '' }}">
                            <input type="hidden" name="array[section][lable_x][lable]" class="form-control" value="{{ $json_data->section->lable_x->lable ?? '' }}">
                            <input type="hidden" name="array[section][lable_x][type]" class="form-control" value="{{ $json_data->section->lable_x->type ?? '' }}">
                            <input type="hidden" name="array[section][lable_x][placeholder]" class="form-control" value="{{ $json_data->section->lable_x->placeholder ?? '' }}">
                            @if (isset($json_data->section->lable_x->text) && (is_array($json_data->section->lable_x->text) || is_object($json_data->section->lable_x->text)) && isset($json_data->section->lable_x->loop_number))
                                <hr/>   
                                @for($i=0; $i<$json_data->section->lable_x->loop_number; $i++)
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label class="form-label">{{ $json_data->section->lable_x->lable .' '. ($i+1) }}</label>
                                            <input type="text" name="array[section][lable_x][text][{{ $i }}]" class="form-control" id="{{ ($json_data->section->lable_x->lable ?? '').'_'.$i }}" value="{{ $json_data->section->lable_x->text->{$i} ?? '' }}">
                                        </div>
                                    </div>
                                </div>
                                @endfor
                            @else
                            <input type="text" name="array[section][lable_x][text]" class="form-control" value="{{ $json_data->section->lable_x->text ?? '' }}"
                                    placeholder="{{ $json_data->section->lable_x->placeholder }}" id="{{ $json_data->section->lable_x->slug }}">
                            @endif
                            

                        </div>
                    </div>
                    @endif
                    @if(isset($json_data->section->lable_y))
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label class="form-label">{{ $json_data->section->lable_y->lable }}</label>
                            <input type="hidden" name="array[section][lable_y][slug]" class="form-control" value="{{ $json_data->section->lable_y->slug ?? '' }}">
                            <input type="hidden" name="array[section][lable_y][lable]" class="form-control" value="{{ $json_data->section->lable_y->lable ?? '' }}">
                            <input type="hidden" name="array[section][lable_y][type]" class="form-control" value="{{ $json_data->section->lable_y->type ?? '' }}">
                            <input type="hidden" name="array[section][lable_y][placeholder]" class="form-control" value="{{ $json_data->section->lable_y->placeholder ?? '' }}">
                            
                                    @if (isset($json_data->section->lable_y->text) && (is_array($json_data->section->lable_y->text) || is_object($json_data->section->lable_y->text)) && isset($json_data->section->lable_y->loop_number))
                                <hr/>   
                                @for($i=0; $i<$json_data->section->lable_y->loop_number; $i++)
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label class="form-label">{{ $json_data->section->lable_y->lable .' '. ($i+1) }}</label>
                                            <input type="text" name="array[section][lable_y][text][{{ $i }}]" class="form-control" id="{{ ($json_data->section->lable_y->lable ?? '').'_'.$i }}" value="{{ $json_data->section->lable_y->text->{$i} ?? '' }}">
                                        </div>
                                    </div>
                                </div>
                                @endfor
                            @else
                            <input type="text" name="array[section][lable_y][text]" class="form-control" value="{{ $json_data->section->lable_y->text ?? '' }}"
                                    placeholder="{{ $json_data->section->lable_y->placeholder }}" id="{{ $json_data->section->lable_y->slug }}">
                            @endif

                        </div>
                    </div>
                    @endif
                    @if(isset($json_data->section->copy_right))
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label class="form-label">{{ $json_data->section->copy_right->lable }}</label>
                            <input type="hidden" name="array[section][copy_right][slug]" class="form-control" value="{{ $json_data->section->copy_right->slug ?? '' }}">
                            <input type="hidden" name="array[section][copy_right][lable]" class="form-control" value="{{ $json_data->section->copy_right->lable ?? '' }}">
                            <input type="hidden" name="array[section][copy_right][type]" class="form-control" value="{{ $json_data->section->copy_right->type ?? '' }}">
                            <input type="hidden" name="array[section][copy_right][placeholder]" class="form-control" value="{{ $json_data->section->copy_right->placeholder ?? '' }}">
                            <input type="text" name="array[section][copy_right][text]" class="form-control" value="{{ $json_data->section->copy_right->text ?? '' }}"
                                    placeholder="{{ $json_data->section->copy_right->placeholder }}" id="{{ $json_data->section->copy_right->slug }}">

                        </div>
                    </div>
                    @endif
                    @if(isset($json_data->section->button))
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label class="form-label">{{ $json_data->section->button->lable }}</label>
                            <input type="hidden" name="array[section][button][slug]" class="form-control" value="{{ $json_data->section->button->slug ?? '' }}">
                            <input type="hidden" name="array[section][button][lable]" class="form-control" value="{{ $json_data->section->button->lable ?? '' }}">
                            <input type="hidden" name="array[section][button][type]" class="form-control" value="{{ $json_data->section->button->type ?? '' }}">
                            <input type="hidden" name="array[section][button][placeholder]" class="form-control" value="{{ $json_data->section->button->placeholder ?? '' }}">
                            @if (isset($json_data->section->button->text) && (is_array($json_data->section->button->text) || is_object($json_data->section->button->text)) && isset($json_data->section->button->loop_number))
                                <hr/>   
                                @for($i=0; $i<$json_data->section->button->loop_number; $i++)
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label class="form-label">{{ $json_data->section->button->lable .' '. ($i+1) }}</label>
                                            <input type="text" name="array[section][button][text][{{ $i }}]" class="form-control" id="{{ ($json_data->section->button->lable ?? '').'_'.$i }}" value="{{ $json_data->section->button->text->{$i} ?? '' }}">
                                        </div>
                                    </div>
                                </div>
                                @endfor
                            @else
                            <input type="text" name="array[section][button][text]" class="form-control" value="{{ $json_data->section->button->text ?? '' }}"
                                    placeholder="{{ $json_data->section->button->placeholder }}" id="{{ $json_data->section->button->slug }}">
                            @endif
                            

                        </div>
                    </div>
                    @endif
                    @if(isset($json_data->section->button_second))
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label class="form-label">{{ $json_data->section->button_second->lable }}</label>
                                <input type="hidden" name="array[section][button_second][slug]" class="form-control" value="{{ $json_data->section->button_second->slug ?? '' }}">
                                <input type="hidden" name="array[section][button_second][lable]" class="form-control" value="{{ $json_data->section->button_second->lable ?? '' }}">
                                <input type="hidden" name="array[section][button_second][type]" class="form-control" value="{{ $json_data->section->button_second->type ?? '' }}">
                                <input type="hidden" name="array[section][button_second][placeholder]" class="form-control" value="{{ $json_data->section->button_second->placeholder ?? '' }}">
                                <input type="text" name="array[section][button_second][text]" class="form-control" value="{{ $json_data->section->button_second->text ?? '' }}"
                                        placeholder="{{ $json_data->section->button_second->placeholder }}" id="{{ $json_data->section->button_second->slug ?? '' }}">

                            </div>
                        </div>
                        @endif
                    @if(isset($json_data->section->description))
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label class="form-label">{{ $json_data->section->description->lable }}</label>
                            <input type="hidden" name="array[section][description][slug]" class="form-control" value="{{ $json_data->section->description->slug ?? '' }}">
                            <input type="hidden" name="array[section][description][lable]" class="form-control" value="{{ $json_data->section->description->lable ?? '' }}">
                            <input type="hidden" name="array[section][description][type]" class="form-control" value="{{ $json_data->section->description->type ?? '' }}">
                            <input type="hidden" name="array[section][description][placeholder]" class="form-control" value="{{ $json_data->section->description->placeholder ?? '' }}">
                            <textarea type="text" name="array[section][description][text]" class="form-control"
                                    placeholder="{{ $json_data->section->description->placeholder }}" id="{{ $json_data->section->description->slug }}"> {{ $json_data->section->description->text }} </textarea>

                        </div>
                    </div>
                    @endif
                    @if(isset($json_data->section->newsletter_title))
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label class="form-label">{{ $json_data->section->newsletter_title->lable }}</label>
                            <input type="hidden" name="array[section][newsletter_title][slug]" class="form-control" value="{{ $json_data->section->newsletter_title->slug ?? '' }}">
                            <input type="hidden" name="array[section][newsletter_title][lable]" class="form-control" value="{{ $json_data->section->newsletter_title->lable ?? '' }}">
                            <input type="hidden" name="array[section][newsletter_title][type]" class="form-control" value="{{ $json_data->section->newsletter_title->type ?? '' }}">
                            <input type="hidden" name="array[section][newsletter_title][placeholder]" class="form-control" value="{{ $json_data->section->newsletter_title->placeholder ?? '' }}">
                            <input type="text" name="array[section][newsletter_title][text]" class="form-control"
                                    placeholder="{{ $json_data->section->newsletter_title->placeholder }}" id="{{ $json_data->section->newsletter_title->slug }}" value="{{ $json_data->section->newsletter_title->text ?? '' }}">

                        </div>
                    </div>
                    @endif
                    @if(isset($json_data->section->newsletter_sub_title))
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label class="form-label">{{ $json_data->section->newsletter_sub_title->lable }}</label>
                            <input type="hidden" name="array[section][newsletter_sub_title][slug]" class="form-control" value="{{ $json_data->section->newsletter_sub_title->slug ?? '' }}">
                            <input type="hidden" name="array[section][newsletter_sub_title][lable]" class="form-control" value="{{ $json_data->section->newsletter_sub_title->lable ?? '' }}">
                            <input type="hidden" name="array[section][newsletter_sub_title][type]" class="form-control" value="{{ $json_data->section->newsletter_sub_title->type ?? '' }}">
                            <input type="hidden" name="array[section][newsletter_sub_title][placeholder]" class="form-control" value="{{ $json_data->section->newsletter_sub_title->placeholder ?? '' }}">
                            <textarea type="text" name="array[section][newsletter_sub_title][text]" class="form-control"
                                    placeholder="{{ $json_data->section->newsletter_sub_title->placeholder }}" id="{{ $json_data->section->newsletter_sub_title->slug }}"> {{ $json_data->section->newsletter_sub_title->text }} </textarea>

                        </div>
                    </div>
                    @endif
                    @if(isset($json_data->section->newsletter_description))
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label class="form-label">{{ $json_data->section->newsletter_description->lable }}</label>
                            <input type="hidden" name="array[section][newsletter_description][slug]" class="form-control" value="{{ $json_data->section->newsletter_description->slug ?? '' }}">
                            <input type="hidden" name="array[section][newsletter_description][lable]" class="form-control" value="{{ $json_data->section->newsletter_description->lable ?? '' }}">
                            <input type="hidden" name="array[section][newsletter_description][type]" class="form-control" value="{{ $json_data->section->newsletter_description->type ?? '' }}">
                            <input type="hidden" name="array[section][newsletter_description][placeholder]" class="form-control" value="{{ $json_data->section->newsletter_description->placeholder ?? '' }}">
                            <textarea type="text" name="array[section][newsletter_description][text]" class="form-control"
                                    placeholder="{{ $json_data->section->newsletter_description->placeholder }}" id="{{ $json_data->section->newsletter_description->slug }}"> {{ $json_data->section->newsletter_description->text }} </textarea>

                        </div>
                    </div>
                    @endif
                    @if(isset($json_data->section->footer_link))
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label class="form-label">{{ $json_data->section->footer_link->lable }}</label>
                            <input type="hidden" name="array[section][footer_link][slug]" class="form-control" value="{{ $json_data->section->footer_link->slug ?? '' }}">
                            <input type="hidden" name="array[section][footer_link][lable]" class="form-control" value="{{ $json_data->section->footer_link->lable ?? '' }}">
                            <input type="hidden" name="array[section][footer_link][type]" class="form-control" value="{{ $json_data->section->footer_link->type ?? '' }}">
                            <input type="hidden" name="array[section][footer_link][text]" class="form-control" value="{{ $json_data->section->footer_link->text ?? '' }}">
                            <input type="hidden" name="array[section][footer_link][loop_number]" class="form-control" value="{{ $json_data->section->footer_link->loop_number ?? '' }}">
                            <hr/>
                            @if (isset($json_data->section->footer_link->type) && ($json_data->section->footer_link->type == 'array') && isset($json_data->section->footer_link->loop_number))
                                @for($i=0; $i<$json_data->section->footer_link->loop_number; $i++)
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label class="form-label">{{ __('Social Link') }}</label>
                                            <input type="text" name="array[section][footer_link][social_link][{{ $i }}]" class="form-control" id="social_link_{{ $i }}" value="{{ $json_data->section->footer_link->social_link->{$i} ?? '' }}">
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label class="form-label">{{ __('Social Icon') }}</label>
                                            <input type="file" name="array[section][footer_link][social_icon][{{ $i }}][text]" class="form-control" id="social_icon_{{ $i }}">
                                            <input type="hidden" name="array[section][footer_link][social_icon][{{ $i }}][image]" class="form-control" id="social_icon_{{ $i }}" value="{{ $json_data->section->footer_link->social_icon->{$i}->image ?? '' }}">

                                            <img src="{{ asset($json_data->section->footer_link->social_icon->{$i}->image ?? '') }}" class="{{ 'social_icon_'. $i .'_preview' }} social_icon" accept="image/*">
                                        </div>
                                    </div>
                                </div>
                                @endfor
                           @endif
                        </div>
                    </div>
                    @endif
                    @if(isset($json_data->section->image))
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label class="form-label">{{ $json_data->section->image->lable }}</label>
                            <input type="hidden" name="array[section][image][slug]" class="form-control" value="{{ $json_data->section->image->slug ?? '' }}">
                            <input type="hidden" name="array[section][image][lable]" class="form-control" value="{{ $json_data->section->image->lable ?? '' }}">
                            <input type="hidden" name="array[section][image][type]" class="form-control" value="{{ $json_data->section->image->type ?? '' }}">
                            <input type="hidden" name="array[section][image][placeholder]" class="form-control" value="{{ $json_data->section->image->placeholder ?? '' }}">
                            @if (isset($json_data->section->image->image) && (is_array($json_data->section->image->image) || is_object($json_data->section->image->image)) && isset($json_data->section->image->loop_number))
                                <hr/>   
                                @for($i=0; $i<$json_data->section->image->loop_number; $i++)
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label class="form-label">{{ $json_data->section->image->lable .' '. ($i+1) }}</label>
                                            <input type="hidden" name="array[section][image][image][{{$i}}]" class="form-control" value="{{ $json_data->section->image->image->{$i} ?? ''    }}">
                            <input type="file" name="array[section][image][text][{{$i}}]" class="form-control"
                                    placeholder="{{ $json_data->section->image->placeholder }}" id="{{ $json_data->section->image->slug }}" multiple>

                                    <img src="{{ asset($json_data->section->image->image->{$i}) }}" style="width: 100px; height: 100px;" class="{{ $json_data->section->image->slug. $i .'_preview' }}" accept="image/*" multiple>
                                        </div>
                                    </div>
                                </div>
                                @endfor
                            @else
                                @if (is_array($json_data->section->image->image) || is_object($json_data->section->image->image))
                                <input type="hidden" name="array[section][image][image][]" class="form-control" value="{{ json_encode($json_data->section->image->image ?? []) }}">
                                <input type="file" name="array[section][image][text][]" class="form-control"
                                        placeholder="{{ $json_data->section->image->placeholder }}" id="{{ $json_data->section->image->slug }}" multiple>

                                        @foreach(objectToArray($json_data->section->image->image) as $key => $image)
                                        <img src="{{ asset($image) }}" style="width: 200px; height: 200px;" class="{{ $json_data->section->image->slug. $key .'_preview' }}" accept="image/*" multiple>
                                        @endforeach

                                @else
                                <input type="hidden" name="array[section][image][image]" class="form-control" value="{{ $json_data->section->image->image ?? '' }}">
                                <input type="file" name="array[section][image][text]" class="form-control"
                                        placeholder="{{ $json_data->section->image->placeholder }}" id="{{ $json_data->section->image->slug }}"  accept="image/*, video/*">
                                <img src="{{ asset($json_data->section->image->image) }}" style="width: 200px; height: 200px;" class="{{ $json_data->section->image->slug.'_preview' }}" accept="image/*">
                                @endif
                            @endif
                        </div>
                    </div>
                    @endif
                    @if(isset($json_data->section->video))
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label class="form-label">{{ $json_data->section->video->lable }}</label>
                            <input type="hidden" name="array[section][video][slug]" class="form-control" value="{{ $json_data->section->video->slug ?? '' }}">
                            <input type="hidden" name="array[section][video][lable]" class="form-control" value="{{ $json_data->section->video->lable ?? '' }}">
                            <input type="hidden" name="array[section][video][type]" class="form-control" value="{{ $json_data->section->video->type ?? '' }}">
                            <input type="hidden" name="array[section][video][placeholder]" class="form-control" value="{{ $json_data->section->video->placeholder ?? '' }}">
                            @if (isset($json_data->section->video->image) && (is_array($json_data->section->video->image) || is_object($json_data->section->image->image)) && isset($json_data->section->image->loop_number))
                                <hr/>   
                                @for($i=0; $i<$json_data->section->video->loop_number; $i++)
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label class="form-label">{{ $json_data->section->video->lable .' '. ($i+1) }}</label>
                                            <input type="hidden" name="array[section][video][image][{{$i}}]" class="form-control" value="{{ $json_data->section->video->image->{$i} ?? ''    }}">
                            <input type="file" name="array[section][video][text][{{$i}}]" class="form-control"
                                    placeholder="{{ $json_data->section->video->placeholder }}" id="{{ $json_data->section->video->slug }}">

                                    <video src="{!! asset($json_data->section->video->image->{$i} ?? '') !!}" id="{{  ($json_data->section->section->video->slug ?? '').'_'. $key }}_preview" loop="" autoplay="" muted="muted" playsinline=""
                    controlslist="nodownload"></video>
                                        </div>
                                    </div>
                                </div>
                                @endfor
                            @else
                                @if (is_array($json_data->section->video->image) || is_object($json_data->section->video->image))
                                <input type="hidden" name="array[section][video][image][]" class="form-control" value="{{ json_encode($json_data->section->video->image ?? []) }}">
                                <input type="file" name="array[section][video][text][]" class="form-control"
                                        placeholder="{{ $json_data->section->video->placeholder }}" id="{{ $json_data->section->video->slug }}">

                                        @foreach(objectToArray($json_data->section->video->image) as $key => $image)

                                        <video src="{!! asset($image ?? '') !!}" id="{{  ($json_data->section->section->video->slug ?? '').'_'. $key}}_preview" loop="" autoplay="" muted="muted" playsinline=""
                    controlslist="nodownload"></video>
                                        @endforeach

                                @else
                                <input type="hidden" name="array[section][video][image]" class="form-control" value="{{ $json_data->section->video->image ?? '' }}">
                                <input type="file" name="array[section][video][text]" class="form-control"
                                        placeholder="{{ $json_data->section->video->placeholder }}" id="{{ $json_data->section->video->slug }}"  accept="video/*">
                                <video src="{{ asset($json_data->section->video->image) }}" id="{{  $json_data->section->section->video->slug ?? '' }}_preview" loop="" autoplay="" muted="muted" playsinline="" controlslist="nodownload"></video>
                                @endif
                            @endif

                        </div>
                    </div>
                    @endif

                    @if(isset($json_data->section->image_left))
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label class="form-label">{{ $json_data->section->image_left->lable }}</label>
                            <input type="hidden" name="array[section][image_left][slug]" class="form-control" value="{{ $json_data->section->image_left->slug ?? '' }}">
                            <input type="hidden" name="array[section][image_left][lable]" class="form-control" value="{{ $json_data->section->image_left->lable ?? '' }}">
                            <input type="hidden" name="array[section][image_left][type]" class="form-control" value="{{ $json_data->section->image_left->type ?? '' }}">
                            <input type="hidden" name="array[section][image_left][placeholder]" class="form-control" value="{{ $json_data->section->image_left->placeholder ?? '' }}">
                            @if (is_array($json_data->section->image_left->image) || is_object($json_data->section->image_left->image))
                            <input type="hidden" name="array[section][image_left][image][]" class="form-control" value="{{ json_encode($json_data->section->image_left->image ?? []) }}">
                            <input type="file" name="array[section][image_left][text][]" class="form-control"
                                    placeholder="{{ $json_data->section->image_left->placeholder }}" id="{{ $json_data->section->image_left->slug }}" multiple>

                                    @foreach(objectToArray($json_data->section->image_left->image) as $key => $image)
                                    <img src="{{ asset($image) }}" style="width: 200px; height: 200px;" class="{{ $json_data->section->image_left->slug. $key .'_preview' }}" accept="image/*" multiple>
                                    @endforeach

                            @else
                            <input type="hidden" name="array[section][image_left][image]" class="form-control" value="{{ $json_data->section->image_left->image ?? '' }}">
                            <input type="file" name="array[section][image_left][text]" class="form-control"
                                    placeholder="{{ $json_data->section->image_left->placeholder }}" id="{{ $json_data->section->image_left->slug }}">
                            <img src="{{ asset($json_data->section->image_left->image) }}" style="width: 200px; height: 200px;" class="{{ $json_data->section->image_left->slug.'_preview' }}" accept="image/*">
                            @endif

                        </div>
                    </div>
                    @endif
                    @if(isset($json_data->section->image_right))
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label class="form-label">{{ $json_data->section->image_right->lable }}</label>
                            <input type="hidden" name="array[section][image_right][slug]" class="form-control" value="{{ $json_data->section->image_right->slug ?? '' }}">
                            <input type="hidden" name="array[section][image_right][lable]" class="form-control" value="{{ $json_data->section->image_right->lable ?? '' }}">
                            <input type="hidden" name="array[section][image_right][type]" class="form-control" value="{{ $json_data->section->image_right->type ?? '' }}">
                            <input type="hidden" name="array[section][image_right][placeholder]" class="form-control" value="{{ $json_data->section->image_right->placeholder ?? '' }}">
                            @if (is_array($json_data->section->image_right->image) || is_object($json_data->section->image_right->image))
                            <input type="hidden" name="array[section][image_right][image][]" class="form-control" value="{{ json_encode($json_data->section->image_right->image ?? []) }}">
                            <input type="file" name="array[section][image_right][text][]" class="form-control"
                                    placeholder="{{ $json_data->section->image_right->placeholder }}" id="{{ $json_data->section->image_right->slug }}" multiple>

                                    @foreach(objectToArray($json_data->section->image_right->image) as $key => $image)
                                    <img src="{{ asset($image) }}" style="width: 200px; height: 200px;" class="{{ $json_data->section->image_right->slug. $key .'_preview' }}" accept="image/*" multiple>
                                    @endforeach

                            @else
                            <input type="hidden" name="array[section][image_right][image]" class="form-control" value="{{ $json_data->section->image_right->image ?? '' }}">
                            <input type="file" name="array[section][image_right][text]" class="form-control"
                                    placeholder="{{ $json_data->section->image_right->placeholder }}" id="{{ $json_data->section->image_right->slug }}">
                            <img src="{{ asset($json_data->section->image_right->image) }}" style="width: 200px; height: 200px;" class="{{ $json_data->section->image_right->slug.'_preview' }}" accept="image/*">
                            @endif

                        </div>
                    </div>
                    @endif
                    @if (isset($json_data->section->product_type))
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label
                                class="form-label">{{ $json_data->section->product_type->lable }}</label>
                            <input type="hidden" name="array[section][product_type][slug]" class="form-control" value="{{ $json_data->section->product_type->slug ?? '' }}">
                            <input type="hidden" name="array[section][product_type][lable]" class="form-control" value="{{ $json_data->section->product_type->lable ?? '' }}">
                            <input type="hidden" name="array[section][product_type][type]" class="form-control" value="{{ $json_data->section->product_type->type ?? '' }}">
                            <input type="hidden" name="array[section][product_type][placeholder]" class="form-control" value="{{ $json_data->section->product_type->placeholder ?? '' }}">
                            <select class="form-control"
                                    name="array[section][product_type][text]"
                                    rows="3" id="{{ $json_data->section->product_type->slug }}">
                                <option value>Select Option</option>
                                    @foreach(config('theme_form_options.product') as $key => $option)
                                    <option value="{{ $key }}" {{ ($key == $json_data->section->product_type->text) ? 'selected' : '' }}>{{ $option }}</option>
                                    @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label class="form-label product_ids">{{ __('Custom Products') }}</label>
                            @php
                                $productIds = isset($json_data->section->product_type->product_ids) && is_object($json_data->section->product_type->product_ids) ? (array) $json_data->section->product_type->product_ids: [];
                                if (!isset($json_data->section->product_type->product_ids)) {
                                    $productIds = $json_data->section->product_ids ?? [];
                                }
                            @endphp
                                <select class="form-control product_ids"
                                        name="array[section][product_type][product_ids][]"
                                        id="product_ids" multiple>
                                        <option value>Select Option</option>
                                        @foreach($produtcs as $product)
                                        <option value="{{ $product->id }}" {{ in_array($product->id, $productIds) ? 'selected' : '' }}>{{ $product->name }}</option>
                                        @endforeach
                                </select>
                        </div>
                    </div>
                    @endif
                   {{-- @if (isset($json_data->section->category_list))
                    <div class="col-sm-12">
                        <div class="form-group">

                            <label class="form-label category_ids">{{ __('Product Categories ') }}</label>
                            @php
                                $categoryIds = isset($json_data->section->category_list->category_ids) && is_object($json_data->section->category_list->category_ids) ? (array) $json_data->section->category_list->category_ids : [];

                                if (!isset($json_data->section->category_list->category_ids)) {
                                    $categoryIds = $json_data->section->category_ids ?? [];
                                }
                            @endphp
                                <select class="form-control category_ids"
                                        name="array[section][category_list][category_ids][]"
                                        id="category_ids" multiple>
                                        <option value>Select Option</option>
                                        <option value="0" selected>All Products</option>
                                        @foreach($categories as $key => $category)
                                        <option value="{{ $key }}" {{ in_array($key, $categoryIds) ? 'selected' : '' }}>{{ $category }}</option>
                                        @endforeach
                                </select>
                        </div>
                    </div>
                    @endif --}}
                    @if (isset($json_data->section->menu_type))
                        @php
                            $menuIds = isset($json_data->section->menu_type->menu_ids) ? (array) $json_data->section->menu_type->menu_ids : [];
                            if (empty($menuIds)) {
                                $menuIds = isset($json_data->section->menu_ids) ? (array) $json_data->section->menu_ids : [];
                            }
                        @endphp

                        <label class="form-label">{{ __('Select Menu ') }}</label>
                        <select class="form-control menu_ids"
                                name="array[section][menu_type][menu_ids][]"
                                id="menu_ids">
                            <option value>Select Option</option>
                            @foreach($menus as $menu)
                                <option value="{{ $menu->id }}" {{ in_array($menu->id, $menuIds) ? 'selected' : '' }}>{{ $menu->name }}</option>
                            @endforeach
                        </select>
                    @endif

                    @if(isset($json_data->section->footer_menu_type))
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label class="form-label">{{ $json_data->section->footer_menu_type->lable }}</label>
                                <input type="hidden" name="array[section][footer_menu_type][slug]" class="form-control" value="{{ $json_data->section->footer_menu_type->slug ?? '' }}">
                                <input type="hidden" name="array[section][footer_menu_type][lable]" class="form-control" value="{{ $json_data->section->footer_menu_type->lable ?? '' }}">
                                <input type="hidden" name="array[section][footer_menu_type][type]" class="form-control" value="{{ $json_data->section->footer_menu_type->type ?? '' }}">
                                <input type="hidden" name="array[section][footer_menu_type][text]" class="form-control" value="{{ $json_data->section->footer_menu_type->text ?? '' }}">
                                <input type="hidden" name="array[section][footer_menu_type][loop_number]" class="form-control" value="{{ $json_data->section->footer_menu_type->loop_number ?? '' }}">
                                <hr/>
                                @if (isset($json_data->section->footer_menu_type->type) && ($json_data->section->footer_menu_type->type == 'array') && isset($json_data->section->footer_menu_type->loop_number))
                                    @for($i=0; $i<$json_data->section->footer_menu_type->loop_number; $i++)
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label class="form-label">{{ __('Footer Title') }}</label>
                                                <input type="text" name="array[section][footer_menu_type][footer_title][{{ $i }}]" class="form-control" id="footer_title_{{ $i }}" value="{{ $json_data->section->footer_menu_type->footer_title->{$i} ?? '' }}" placeholder="Enter text here....">
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label class="form-label">{{ __('Footer Menu') }}</label>
                                                @php
                                                    $menuIds = isset($json_data->section->footer_menu_type->footer_menu_ids) && is_object($json_data->section->footer_menu_type->footer_menu_ids)
                                                        ? (array) $json_data->section->footer_menu_type->footer_menu_ids : [];

                                                    if (!isset($json_data->section->footer_menu_type->footer_menu_ids)) {
                                                        $menuIds = $json_data->section->footer_menu_type->footer_menu_ids ?? [];
                                                    }
                                                @endphp
                                                <select class="form-control" name="array[section][footer_menu_type][footer_menu_ids][]" id="">
                                                    <option value>Select Option</option>
                                                    
                                                    @foreach($menus as $menu)
                                                        <option value="{{ $menu->id }}" {{ in_array($menu->id, $menuIds) ? 'selected' : '' }}>{{ $menu->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    @endfor
                            @endif
                            </div>
                        </div>
                    @endif
                @endif
            </div>
            @endfor
        </div>
        <div class="card-footer">
            @if (isset($json_data->section_slug) && $json_data->section_slug == 'slider' && $json_data->array_type == 'multi-inner-list')
            <div class="row">
                <div class="col-sm-12 text-end">
                    <button class="btn btn-primary add-new-slider-btn">Add New Slider</button>
                    <button class="btn btn-danger delete-slider-btn" @if(isset($json_data->loop_number) && $json_data->loop_number < 4) disabled="true" @endif>Delete Slider</button>
                </div>
            </div>
            @endif
        </div>
    </div>
    {!! Form::close() !!}
@endif
</div>
