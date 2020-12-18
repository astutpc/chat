<?php

namespace App\Events;

use App\User;
use App\Message;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;

class SendMessage implements ShouldBroadcastNow 
{
    use SerializesModels;
    
    public $user;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PresenceChannel('message'.$this->user->id);
    }
    public function broadcastWith () {
        return [
            'id'       => $this->user->id,
            'name'     => $this->user->name,
            
        ];
    }
    /**
     * The event's broadcast name.
     *
     * @return string
     */
    public function broadcastAs()
    {
        return 'Chat';
    }
    // /**
    //  * The event's broadcast name.
    //  *
    //  * @return string
    //  */
    // public function broadcastWith()
    // {
    //     return ['title'=>'This notification from ItSolutionStuff.com'];
    // }
}