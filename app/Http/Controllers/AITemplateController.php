<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Template;
use Orhanerday\OpenAi\OpenAi;
use App\Models\Utility;
use App\Models\ApikeySetiings;
use Illuminate\Support\Facades\DB;

class AITemplateController extends Controller
{
    public function create($moduleName)
    { 
        $templateName = Template::where('module',$moduleName)->get();
        return view('generate', compact('templateName'));
    }
    public function getKeywords(Request $request, $id)
    {
        $template = Template::find($id);
        $field_data = json_decode($template->field_json);
        $html = "";
        foreach ($field_data->field as  $value) {
            $html .= '<div class="form-group col-md-12">
                     <label class="form-label ">' . $value->label . '</label>';
            if ($value->field_type == "text_box") {

                $html .= '<input type="text" class="form-control" name="' . $value->field_name . '" value="" placeholder="' . $value->placeholder . '" required">';
            }
            if ($value->field_type == "textarea") {
                $html .= '<textarea type="text" rows=3 class="form-control " id="description" name="' . $value->field_name . '" placeholder="' . $value->placeholder . '" required></textarea>';
            }
            $html .= '</div>';
        }
        return response()->json(
            [
                'success' => true,
                'template' => $html,
                'tone'=>$template->is_tone
            ]
        );
    }
    public function aiGenerate(Request $request)
    {
        if ($request->ajax()) {
            $post = $request->all();

            unset($post['_token'], $post['template_name'], $post['tone'], $post['ai_creativity'], $post['num_of_result'], $post['result_length']);
            $data = array();
            $key_data = ApikeySetiings::inRandomOrder()->limit(1)->first();
            if (empty($key_data)) {
                $key_data = DB::table('settings')->where('name', 'chatgpt_key')->first();
                $key_data = $key_data->value ?? null;
            } else {
                $key_data = $key_data->key ?? null;
            }
            
            if ($key_data) {

                $open_ai = new OpenAi($key_data);
            } else {
                $data['status'] = 'error';
                $data['message'] = __('Please set proper configuration for Api Key');
                return $data;
            }
            
            $aiCustomTemplate = '';
            $model = '';
            $text = '';
            $aitokens = '';
            $counter = 1;


            $template = Template::where('id', $request->template_name)->first();

            if ($request->template_name) {


                $required_field = array();
                $data_field = json_decode($template->field_json);
                foreach ($data_field->field as  $val) {
                    request()->validate([$val->field_name => 'required|string']);
                }

                $aiCustomTemplate = $template->prompt;
                foreach ($data_field->field as  $field) {

                    $text_rep = "##" . $field->field_name . "##";
                    if (strpos($aiCustomTemplate, $text_rep) !== false) {
                        $field->value = $post[$field->field_name];
                        $aiCustomTemplate = str_replace($text_rep, $post[$field->field_name], $aiCustomTemplate);
                    }
                    if ($template->is_tone == 1) {
                        $tone = $request->tone;
                        $param = "##tone_language##";
                        $aiCustomTemplate = str_replace($param, $tone, $aiCustomTemplate);
                    }
                }
            }
            $lang_text = "Provide response in " . $request->language . " language.\n\n ";
            $aitokens = (int)$request->result_length;

            $max_results = (int)$request->num_of_result;
            $ai_creativity = (float)$request->ai_creativity;
            $complete = $open_ai->completion([
                'model' => 'text-davinci-003',
                'prompt' => $aiCustomTemplate . ' ' . $lang_text,
                'temperature' => $ai_creativity,
                'max_tokens' => $aitokens,
                'n' => $max_results
            ]);
            $response = json_decode($complete, true);
            if (isset($response['choices'])) {
                if (count($response['choices']) > 1) {
                    foreach ($response['choices'] as $value) {
                        $text .= $counter . '. ' . ltrim($value['text']) . "\r\n\r\n\r\n";
                        $counter++;
                    }
                } else {
                    $text = $response['choices'][0]['text'];
                }

                $tokens = $response['usage']['completion_tokens'];
                $data = trim($text);
                return $data;
            } else {
                $data['status'] = 'error';
                $data['message'] = __('Text was not generated due to Invalid API Key');
                return $data;
            }
        }
    }
}
