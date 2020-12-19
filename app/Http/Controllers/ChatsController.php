<?php

namespace App\Http\Controllers;

use App\Events\SendMessage;
use Auth;
use Config;

use Carbon\Carbon;

use App\User;
use App\Message;

use Spatie\Permission\Models\Role;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


Class ChatsController extends Controller
{
  

  public function __construct()
  { 
     $this->middleware('auth');
  }
  public function index()
  {
    $data = User::find(Auth::user()->id);
    if(Auth::check()) {
      broadcast(new SendMessage($data))->toOthers();
    }
    $userList = User::get()->pluck('name','id')->toArray();
    return view('chat.index',compact('userList'));
  }
  // public function auth()
  // {
  //   return true;
  // }
  public function userChatList(Request $request)
  {
    if($request->ajax())
    {
        $contact_list = Message::with('toMessage','fromMessage')
        ->where('is_last','1')->where(function ($query) {
              $query->where('from_id', Auth::user()->id)
              ->orWhere('to_id', Auth::user()->id);
        })->orderBy('id','desc')->get();
        $user_list_count = $contact_list->count();
      return response()->json(['success'=>true,'html'=>view('chat.ajax.user-chat-list',compact('contact_list'))->render(),'user_list_count'=>$user_list_count]); 
    }
    return response()->json(['success'=>false,'error'=>'You are not authroised']);
  }

  public function userList(Request $request)
  {
    if(Auth::user()->hasRole('Property Owner'))
    {
      // get applicants in the list
      $applicant = Applicant::with(['property','user'])
      ->whereHas('property',function($query){
        if(Auth::user()->hasRole('Property Owner'))
        {
          $query->where(['owner_id'=>Auth::user()->id]);
        }
        else if(Auth::user()->hasRole('Property Manager'))
        {
          $query->where(['manager_id'=>Auth::user()->id]);
        }
      })->groupBy('user_id')->get()->pluck('user.full_name','user.id');

      echo "<pre>"; print_r($applicant->toArray());

      // get managers in the list
      $managers = Property::with(['manager'])->whereNotNull('manager_id')->where(['owner_id'=>Auth::user()->id])->groupBy('manager_id')->get()->pluck('manager.full_name','manager.id');

      echo "<pre>"; print_r($managers->toArray());

      echo "<pre>"; print_r( $applicant->toArray() + $managers->toArray() ); die;
      


      


      // $query = User::with('propertyManager','propertyOwner.applicant')
      //   ->whereHas('propertyManager',function($query) use ($user_name){
      //       $query->where('owner_id',Auth::user()->id)->groupBy('owner_id');
      //     });
    }
    else if(Auth::user()->hasRole('Property Manager'))
    {
      $query = User::with('propertyOwner','propertyManager.applicant')
      ->whereHas('propertyOwner',function($query){
            $query->where('manager_id',Auth::user()->id)->groupBy('manager_id');
        });
    }
    else if(Auth::user()->hasRole('Tenant'))
    {
        $query = User::with('propertyManager.applicant','propertyOwner.applicant')
        ->whereHas('propertyManager.applicant',function($query){
              $query->where('user_id',Auth::user()->id)->groupBy('user_id');
        })
        ->orWhereHas('propertyOwner.applicant',function($query){
              $query->where('user_id',Auth::user()->id)->groupBy('user_id');
        });
    }

    // $user_list = $query->orWhere('firstname','LIKE','%'.$user_name.'%')
    //   ->orWhere('lastname','LIKE','%'.$user_name.'%')
    //   ->get()
    //   ->except(Auth::user()->id);
    return response()->json(['success'=>true,'user_list'=>$user_list]);
    
  }

    
  public function appendLastMessage(Request $request)
  {
    if($request->ajax() && $request->user_id)
    {
      $user_id = $request->user_id;
      $query = NotificationUser::with('fromMessage','toMessage')
      ->where(function ($query) use ($user_id) {
          $query->where('from_id', $user_id)->where('to_id', Auth::user()->id);
      })->orWhere(function ($query) use ($user_id) {  
          $query->where('from_id', Auth::user()->id)->where('to_id', $user_id);
      });
      $notifyMessage = $query->latest('id')    
      ->first();
      $html = "";
      if($notifyMessage->count()>0)
      {
        
          $logotext = '';
            if($notifyMessage->from_id==Auth::user()->id)
            {
              $html .= '<li class="clearfix odd" id="'.$notifyMessage->id.'_message">';
            }
            else{
                 $html .= '<li class="clearfix" id="'.$notifyMessage->id.'_message">';
            }
            $html .= '<div class="chat-avatar"><span class="avatar-title bg-soft-secondary text-secondary font-10 rounded-circle">';
            $logotext = logoText($notifyMessage->fromMessage->firstname).logoText($notifyMessage->fromMessage->lastname);
            $html .= $logotext.'</span><i>'.setChatDate($notifyMessage->created_at).'</i></div>';
            $html .= '<div class="conversation-text"><div class="ctext-wrap"> <i>'.$notifyMessage->fromMessage->firstname.' '.logoText($notifyMessage->fromMessage->lastname).'</i><p id="'.$notifyMessage->id.'_currentMessage">'.$notifyMessage->message.'</p></div></div>';
            
            if($notifyMessage->from_id==Auth::user()->id)
            {
              $html .= '<div class="conversation-actions dropdown">';
              $html .= '<button class="btn btn-sm btn-link" data-toggle="dropdown" aria-expanded="false"><i class="mdi mdi-dots-vertical font-16"></i></button>';
              $html .= '<div class="dropdown-menu">';
              $html .= '<a class="dropdown-item" href="javascript:void(0);" onclick="actionModel(this,'.$notifyMessage->id.',1)">Edit</a>';
              $html .= '<a class="dropdown-item" href="javascript:void(0)" onclick="actionMessage(this,'.$notifyMessage->id.',0)">Delete</a>';
              $html .= '</div>';
              $html .= '</div>';
            }
            $html .= '</li>';
        
      }
      else{
           $html .='<h3 class="text-center">There is no conversation between both of you.</h3>';
          $reciever_name = "";
          $reciever_logo_text = "";
       }
       NotificationUser::where(['from_id'=>$user_id,'to_id'=>Auth::user()->id])->update(['is_read' => '1']);
      return response()->json(['success'=>true,'html'=>$html,'last_message_id'=>$notifyMessage->id]);
    }
    return response()->json(['success'=>false,'error'=>'You are not authroised']);
  }
  public function sendMessage(Request $request)
  {
      $attributes = $request->all();
      $user_id = Auth::user()->id;
      $data =  User::find($user_id);
      if(Auth::check()) {
          broadcast(new SendMessage($data))->toOthers();
      }
      return response()->json(['success'=>true,'status' => 'success','message'=>'message has been sent'],200) ;
      
        $validateArray = array(
              'user_id'=>'required',
              'message_text'=>'required'
          );
        $validator = Validator::make($attributes, $validateArray);
        if($validator->fails())
        {
            return response()->json(['success' => false, 'type' => 'validation-error', "error" => $validator->errors()]);
        }
        $user_id = Auth::user()->id;
        // $updateLastMessage = Message::where(['from_id'=>Auth::user()->id,'to_id'=>$user_id])
        // ->orWhere(function($query) use($user_id) {
        //     $query->where(['to_id'=>Auth::user()->id,'from_id'=>$user_id]);
        // })->update(['is_last' => '0']);

        $message = new Message();
        $message->from_id = Auth::user()->id;
        $message->to_id = $attributes['user_id'];
        $message->message = $attributes['message_text'];
        $message->last_message = $attributes['message_text'];
        $message->is_read = '0';
        $message->is_last = '1';
        $message->save();
        $data =  User::find($user_id);
       // Message::where(['from_id'=>$attributes['user_id'],'to_id'=>Auth::user()->id])->update(['is_read' => '1']);
        // $logotext = logoText(Auth::user()->firstname).logoText(Auth::user()->lastname);
        // $heading_message_text = Auth::user()->firstname.' '.logoText(Auth::user()->lastname);
        // $chat_date = setChatDate($message->created_at);
        // $last_short_message = limitString($message->message, 10);
        // $data = ['from' => $message->from_id, 'to' => $message->to_id,'last_message'=>$message->message,'message_id'=>$message->id,'logo_text'=>$logotext,'heading_message_text'=>$heading_message_text,'chat_date'=>$chat_date,'last_short_message'=>$last_short_message]; // sending from and to user id when pressed enter
        //$pusher->trigger('my-channel', 'my-event', $data);
        // broadcast(new SendMessage($data))->toOthers();
        if(Auth::check()) {
          broadcast(new SendMessage($data))->toOthers();
        }
        return response()->json(['success'=>true,'message'=>'message has been sent']) ;
   }
   
    public function actionMessage(Request $request)
    {
         if($request->ajax() && $request->message_id)
         {
            $message = NotificationUser::find($request->message_id);
             if($message)
             {
               if($request->status==0)
               {
                $message->delete();
                 if($message->is_last=='1')
                 {
                  $last_message  = NotificationUser::where(['from_id'=>$message->from_id,'to_id'=>$message->to_id])
                  ->orWhere(function($query) use($message) {
                      $query->where(['to_id'=>$message->from_id,'from_id'=>$message->to_id]);
                  })
                  ->latest('id')->first();
                    if(!empty($last_message))
                    {
                      $last_message->is_last = '1';
                      $last_message->save(); 
                    }
                  
                 }
                  
                  return response()->json(['success'=>true,'status'=>'deleted','message'=>'message has been deleted']);
               }
               else if($request->status==1)
               {
                  $message->message = $request->message;
                  $message->save();
                  return response()->json(['success'=>true,'status'=>'edit','message'=>'message has been edit']);
               }
                
             }
              return response()->json(['success'=>false,'error'=>'Message not exist']);
         }
         return response()->json(['success'=>false,'error'=>'You are not authroised']);
    }
    public function fetchMessages(Request $request)
  {
      if($request->ajax() && $request->user_id)
      {
        $user_id = $request->user_id;
        $query = NotificationUser::with('fromMessage','toMessage')
        ->where(function ($query) use ($user_id) {
            $query->where('from_id', $user_id)->where('to_id', Auth::user()->id);
        })->orWhere(function ($query) use ($user_id) {
            $query->where('from_id', Auth::user()->id)->where('to_id', $user_id);
        });
        $max_count = $query->count();
        $notifyMessage = $query->latest()    
        ->paginate(30)->reverse();
        $html = "";
        $reciever_name = "";
        $reciever_logo_text = "";
        $message = $query->latest()->first();
        if($notifyMessage->count()>0)
        {
          if($message->from_id==Auth::user()->id){
              $reciever_name = $message->toMessage->firstname.' '.$message->toMessage->lastname;
              $reciever_logo_text = logoText($message->toMessage->firstname).logoText($message->toMessage->lastname);
            }
            else if($message->to_id==Auth::user()->id)
            {
              $reciever_name = $message->fromMessage->firstname.' '.$message->fromMessage->lastname;
              $reciever_logo_text = logoText($message->fromMessage->firstname).logoText($message->fromMessage->lastname);
            }
        }
        else{
            $max_count = 0;
            $reciever_name = "";
            $reciever_logo_text = "";
        }  
        $user_id = $request->user_id;
        NotificationUser::where(['from_id'=>$user_id,'to_id'=>Auth::user()->id])->update(['is_read' => '1']);
        return response()->json(['success'=>true,'html'=>view('chat.ajax.user-message-list',compact('notifyMessage','max_count','user_id'))->render(),'reciever_name'=>$reciever_name,'reciever_logo_text'=>$reciever_logo_text,'max_count'=>$max_count]);
      }
      return response()->json(['success'=>false,'error'=>'You are not authroised']);
  }
}