<?php $count = Auth::user()->newThreadsCount(); ?>

<span id="unread" class="label label-danger">{{ $count }}</span>
