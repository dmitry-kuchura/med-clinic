<?php

namespace App\Models\Enum;

class MessageStatus
{
    const QUEUED = 'Queued';
    const ACCEPTED = 'Accepted';
    const SENT = 'Sent';
    const DELIVERED = 'Delivered';
    const READ = 'Read';
    const EXPIRED = 'Expired';
    const UNDELIVERED = 'Undelivered';
    const REJECTED = 'Rejected';
    const UNKNOWN = 'Unknown';
    const FAILED = 'Failed';
    const CANCELLED = 'Cancelled';
}
