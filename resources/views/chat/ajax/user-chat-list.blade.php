@if(!empty(@$contact_list)) 
    @foreach($contact_list as $key=>$value)
      <a href="javascript:void(0);" class="text-body receiver {{$value->from_id == Auth::user()->id ? $value->toMessage->id : $value->fromMessage->id}}_user_change_message" id="{{$value->from_id == Auth::user()->id ? $value->toMessage->id : $value->fromMessage->id}}">
          <div class="media p-2 user_message_div_{{$value->from_id == Auth::user()->id ? $value->toMessage->id : $value->fromMessage->id}}">
              <div class="avatar-sm">
                  <span class="avatar-title bg-soft-secondary text-secondary font-12 rounded-circle receiever-name">
                  {{ $value->from_id == Auth::user()->id ? logoText($value->toMessage->firstname).logoText($value->toMessage->lastname) : logoText($value->fromMessage->firstname).logoText($value->fromMessage->lastname )}}
                  </span>
              </div>  
              <div class="media-body m-1">
                  <h5 class="mt-0 mb-0 font-14"> 
                      <span class="float-right text-muted font-weight-normal font-12">{{setChatDate($value->created_at)}}</span>
                      {{ $value->from_id == Auth::user()->id ? $value->toMessage->firstname.' '.$value->toMessage->lastname : $value->fromMessage->firstname.' '.$value->fromMessage->lastname }}
                      <small class="mdi mdi-circle text-danger" id="is_online_{{$value->from_id == Auth::user()->id ? $value->toMessage->id : $value->fromMessage->id}}"></small>
                  </h5>
                  <p class="mt-1 mb-0 text-muted font-14">
                      <span class="w-25 float-right text-right"><span class="badge badge-soft-danger pending" id="{{$value->from_id == Auth::user()->id ? $value->toMessage->id : $value->fromMessage->id}}_pending">{{getUnreadMessageCount( $value->from_id == Auth::user()->id ? $value->toMessage->id : $value->fromMessage->id )}}</span></span>
                      <span class="w-75 {{$value->from_id == Auth::user()->id ? $value->toMessage->id : $value->fromMessage->id}}_user_last_message">{{ limitString($value->message, 10) }}</span>
                  </p>
                </div>
          </div>
      </a>
    @endforeach 
    @else
      <h3 class="text-center">No Conversattion between them</h3>
@endif 
    