<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OrderStatusUpdated extends Notification
{
    use Queueable;

    protected $order;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($order)
    {
        $this->order = $order;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];  // Estamos usando o canal de e-mail
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->subject('Seu pedido foi atualizado!')
                    ->greeting('Olá, ' . $notifiable->name)
                    ->line('O status do seu pedido #' . $this->order->order_number . ' foi alterado para: ' . $this->order->status)
                    ->action('Ver pedido', url('/orders/' . $this->order->id))
                    ->line('Obrigado por comprar conosco!');
    }
}
