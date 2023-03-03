<?php

namespace App\Lib;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class Csendemail extends Mailable
{
	use Queueable, SerializesModels;

	public function __construct($isipesan)
    {
        $this->isipesan = $isipesan;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('pengambengannelayan@gmail.com')
            ->view('email')
            ->with(
            [
                'isipesan' => $this->isipesan
            ]);
    }
}
