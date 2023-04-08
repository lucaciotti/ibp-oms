<?php

namespace App\Http\Livewire\Layouts;

use Auth;
use Livewire\Component;

class NotificationBell extends Component
{
    public $item;
    public $notifyLabel =
    [
        'label'       => 0,
        'label_color' => 'danger',
        'icon_color'  => 'dark',
    ];
    public $openedSlideOver = false;
    private $previousCount = 0;

    protected $listeners = [
        'notification-slide-over-open' => 'toogleSlideOverOpened',
        'slide-over.close' => 'toogleSlideOverClosed',
    ];
    
    public function mount($menuItem) {
        $this->item = $menuItem;
        $this->getNotifications();
    }

    public function render()
    {
        // dd($this->openedSlideOver);
        return view('livewire.layouts.notification-bell');
    }

    public function getNotifications($force=false){
        $notificationsCount = count(Auth::user()->unreadNotifications);
        if ($this->previousCount != $notificationsCount or $force) {
            $this->previousCount = $notificationsCount;
            $this->notifyLabel = [
                'label'       => $notificationsCount,
                'label_color' => 'danger',
                'icon_color'  => 'dark',
            ];
            $this->emit('notifyUpdated');
        }
    }

    public function toogleSlideOverOpened(){
        $this->openedSlideOver = true;
    }
    public function toogleSlideOverClosed(){
        $this->openedSlideOver = false;
        $this->getNotifications(true);
    }
    public function openSlideOver(){
        $this->emit('slide-over.open', 'layouts.notification-slide-over');
        $this->openedSlideOver = true;
    }
    public function closeSlideOver()
    {
        $this->emit('slide-over.close', 'layouts.notification-slide-over');
        $this->openedSlideOver = false;
        $this->getNotifications(true);
    }
}
