<?php

namespace App\Events;

use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CreateInvoice  implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $patient;
    public $invoice_id;
    public $message;
    public $created_at;
    public $doctor_id;  /// new for private channel
    public $patient_id; /// new for private channel


    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $patient = User::find($data['patient_id']);
        $this->patient = $patient->name;
        $this->patient_id = $patient->id;  /// new for private channel
        $this->doctor_id = $data['doctor_id'];  /// new for private channel

        $this->invoice_id = $data['invoice_id'];
        $this->message = "كشف جديد : ";
        $this->created_at =date('Y-m-d H:i:s');
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('create-invoice.'.$this->doctor_id);
    }

    public function broadcastAs(){
        return 'create-invoice'; 
    }

}
