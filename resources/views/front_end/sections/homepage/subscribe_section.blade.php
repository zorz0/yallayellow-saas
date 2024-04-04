
<section class="custom-banner-section-two padding-bottom" style="position: relative;@if(isset($option) && $option->is_hide == 1) opacity: 0.5; @else opacity: 1; @endif" data-index="{{ $option->order ?? '' }}" data-id="{{ $option->order ?? '' }}" data-value="{{ $option->id ?? '' }}" data-hide="{{ $option->is_hide ?? '' }}" data-section="{{ $option->section_name ?? '' }}"  data-store="{{ $option->store_id ?? '' }}" data-theme="{{ $option->theme_id ?? '' }}">
    <div class="custome_tool_bar"></div>
    <div class="container">
        <div class="custom-banner-two" id="{{ $section->subscribe->section->image->slug ?? '' }}_preview"
            style="background-image:url('{{ asset($section->subscribe->section->image->image ?? '') }}');">
            <div class="custom-banner-inner">
                <div class="label" id="{{ $section->subscribe->section->title->slug ?? '' }}_preview">{!! $section->subscribe->section->title->text ?? '' !!}</div>
                <p id="{{ $section->subscribe->section->description->slug ?? '' }}_preview">{!! $section->subscribe->section->description->text ?? '' !!}</p>

                {!! \App\Models\Newsletter::Subscribe($currentTheme, $slug, $section) !!}

            </div>
        </div>
    </div>
</section>
