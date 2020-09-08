<?php

namespace App\Listeners;

use Brexis\LaravelWorkflow\Events\GuardEvent;

class OrderWorkflowSubscriber
{
    /**
     * Handle workflow guard events.
     */
    public function onGuard(GuardEvent $event) {}

    /**
     * Handle workflow leave event.
     */
    public function onLeave($event) {}

    /**
     * Handle workflow transition event.
     */
    public function onTransition($event) {}

    /**
     * Handle workflow enter event.
     */
    public function onEnter($event) {}

    /**
     * Handle workflow entered event.
     */
    public function onEntered($event) {
        /** Symfony\Component\Workflow\Event\GuardEvent */
        $originalEvent = $event->getOriginalEvent();

        /** @var App\Models\Order $order */
        $order = $originalEvent->getSubject();
        
    }

    /**
     * Register the listeners for the subscriber.
     *
     * @param  Illuminate\Events\Dispatcher  $events
     */
    public function subscribe($events)
    {
        $events->listen(
            'Brexis\LaravelWorkflow\Events\GuardEvent',
            'App\Listeners\OrderWorkflowSubscriber@onGuard'
        );

        $events->listen(
            'Brexis\LaravelWorkflow\Events\LeaveEvent',
            'App\Listeners\OrderWorkflowSubscriber@onLeave'
        );

        $events->listen(
            'Brexis\LaravelWorkflow\Events\TransitionEvent',
            'App\Listeners\OrderWorkflowSubscriber@onTransition'
        );

        $events->listen(
            'Brexis\LaravelWorkflow\Events\EnterEvent',
            'App\Listeners\OrderWorkflowSubscriber@onEnter'
        );

        $events->listen(
            'Brexis\LaravelWorkflow\Events\EnteredEvent',
            'App\Listeners\OrderWorkflowSubscriber@onEntered'
        );
    }

}