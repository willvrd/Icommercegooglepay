@php
    $configuration = icommercegooglepay_get_configuration();
    $options = array('required' =>'required');
    
    if($configuration==NULL){
        $cStatus = 0;
        $entity = icommercegooglepay_get_entity();
    }else{
        $cStatus = $configuration->status;
        $entity = $configuration;
    }

    $status = icommerce_get_status();
    $formID = uniqid("form_id");
    
@endphp

{!! Form::open(['route' => ['admin.icommercegooglepay.googlepayconfig.update'], 'method' => 'put','name' => $formID]) !!}

<div class="col-xs-12 col-sm-9">

    @include('icommerce::admin.products.partials.flag-icon',['entity' => $entity,'att' => 'description'])
    
    {!! Form::normalInput('description', trans('icommercegooglepay::googlepayconfigs.table.description'), $errors,$configuration,$options) !!}

    <div class="form-group">
        <div>
            <label class="checkbox-inline">
                <input name="status" type="checkbox" @if($cStatus==1) checked @endif>{{trans('icommercegooglepay::googlepayconfigs.table.activate')}}
            </label>
        </div>   
    </div>

</div>

<div class="col-sm-3">

    @include('icommercegooglepay::admin.googlepayconfigs.partials.featured-img',['crop' => 0,'name' => 'mainimage','action' => 'create'])

</div>
    
    
 <div class="clearfix"></div>   

    <div class="box-footer">
    <button type="submit" class="btn btn-primary btn-flat">{{ trans('icommercegooglepay::googlepayconfigs.button.save configuration') }}</button>
    </div>



{!! Form::close() !!}