<?php

namespace RLuders\Socialize\Modules\Friendship\Traits;

use October\Rain\Database\Model;
use Illuminate\Support\Facades\Event;
use RLuders\Socialize\Modules\Friendship\Classes\Status;
use RLuders\Socialize\Models\Friendship;
use October\Rain\Extension\ExtensionBase;

trait Friendable
{
    /**
     * Who is the sender model?
     *
     * @return Model
     */
    public function getSender()
    {
        return $this;
    }

    /**
     * Create an friendship request
     *
     * @param Model $recipient
     *
     * @return Friendship
     */
    public function befriend(Model $recipient)
    {
        if (!$this->canBeFriend($recipient)) {
            return false;
        }

        Event::fire('friendships.sent', [$this->getSender(), $recipient]);

        return $this->getSender()->friends()->create(
            [
                'recipient_id' => $recipient->id,
                'recipient_type' => get_class($recipient),
                'status' => Status::PENDING
            ]
        );
    }

    /**
     * Remove an friendship
     *
     * @param Model $recipient
     *
     * @return Friendship
     */
    public function unfriend(Model $recipient)
    {
        Event::fire('friendships.cancelled', [$this->getSender(), $recipient]);

        return $this->findFriendship($recipient)->delete();
    }

    /**
     * Check if has friend request from
     *
     * @param Model $recipient
     *
     * @return boolean
     */
    public function hasFriendRequestFrom(Model $recipient)
    {
        return $this->findFriendship($recipient)
            ->whereSender($recipient)
            ->whereStatus(Status::PENDING)
            ->exists();
    }

    /**
     * Check if has sent a friend request to
     *
     * @param Model $recipient
     *
     * @return boolean
     */
    public function hasSentFriendRequestTo(Model $recipient)
    {
        return Friendship::whereRecipient($recipient)
            ->whereSender($this->getSender())
            ->whereStatus(Status::PENDING)
            ->exists();
    }

    /**
     * Check friendship with
     *
     * @param Model $recipient
     *
     * @return boolean
     */
    public function isFriendWith(Model $recipient)
    {
        return $this->findFriendship($recipient)
            ->where('status', Status::ACCEPTED)
            ->exists();
    }

    /**
     * Accept the friendship request
     *
     * @param Model $recipient
     *
     * @return boolean|int
     */
    public function acceptFriendRequest(Model $recipient)
    {
        Event::fire('friendships.accepted', [$this->getSender(), $recipient]);

        return $this->findFriendship($recipient)
            ->whereRecipient($this->getSender())
            ->update(['status' => Status::ACCEPTED]);
    }

    /**
     * Reject a friendship request
     *
     * @param Model $recipient
     *
     * @return boolean|int
     */
    public function denyFriendRequest(Model $recipient)
    {
        Event::fire('friendships.denied', [$this->getSender(), $recipient]);

        return $this->findFriendship($recipient)
            ->whereRecipient($this->getSender())
            ->delete();
    }

    /**
     * Add a friend into a group
     *
     * @param Model  $friend
     * @param string $groupSlug
     *
     * @return boolean
     */
    public function groupFriend(Model $friend, $groupSlug)
    {
        $friendship = $this->findFriendship($friend)->whereStatus(Status::ACCEPTED)->first();
        $groupsAvailable = config('friendships.groups', []);

        if (!isset($groupsAvailable[$groupSlug]) || empty($friendship)) {
            return false;
        }

        $group = $friendship->groups()->firstOrCreate(
            [
                'friendship_id' => $friendship->id,
                'group_id' => $groupsAvailable[$groupSlug],
                'friend_id' => $friend->getKey(),
                'friend_type' => $friend->getMorphClass(),
            ]
        );

        return $group->wasRecentlyCreated;
    }

    /**
     * Removes a friend from a group
     *
     * @param Model  $friend
     * @param string $groupSlug
     *
     * @return boolean
     */
    public function ungroupFriend(Model $friend, $groupSlug = '')
    {

        $friendship = $this->findFriendship($friend)->first();
        $groupsAvailable = config('friendships.groups', []);

        if (empty($friendship)) {
            return false;
        }

        $where = [
            'friendship_id' => $friendship->id,
            'friend_id' => $friend->getKey(),
            'friend_type' => $friend->getMorphClass(),
        ];

        if ('' !== $groupSlug && isset($groupsAvailable[$groupSlug])) {
            $where['group_id'] = $groupsAvailable[$groupSlug];
        }

        return $friendship->groups()->where($where)->delete();
    }

    /**
     * Cancel the friend request to
     *
     * @param Model $recipient
     *
     * @return boolean
     */
    public function cancelFriendRequestTo(Model $recipient)
    {
        Event::fire('friendships.canceled', [$this->getSender(), $recipient]);

        return $this->findFriendship($recipient)->delete();
    }

    /**
     * Block a friend
     *
     * @param Model $recipient
     *
     * @return Friendship
     */
    public function blockFriend(Model $recipient)
    {
        // if there is a friendship between the two users and the sender is not blocked
        // by the recipient user then delete the friendship
        if (!$this->isBlockedBy($recipient)) {
            $this->findFriendship($recipient)->delete();
        }

        $friendship = Friendship::create(['status' => Status::BLOCKED]);

        Event::fire('friendships.blocked', [$this->getSender(), $recipient]);

        return $this->getSender()->friends()->save($friendship);
    }

    /**
     * Unblock a friend
     *
     * @param Model $recipient
     *
     * @return Friendship
     */
    public function unblockFriend(Model $recipient)
    {
        Event::fire('friendships.unblocked', [$this->getSender(), $recipient]);

        return $this->findFriendship($recipient)->whereSender($this->getSender())->delete();
    }

    /**
     * Get a friendship
     *
     * @param Model $recipient
     *
     * @return Friendship
     */
    public function getFriendship(Model $recipient)
    {
        return $this->findFriendship($recipient)->first();
    }

    /**
     * Get all friendships
     *
     * @param string $groupSlug
     *
     * @return Illuminate\Database\Eloquent\Collection|Friendship[]
     */
    public function getAllFriendships($groupSlug = '')
    {
        return $this->findFriendships(null, $groupSlug)->get();
    }

    /**
     * Get all pending friendships
     *
     * @param string $groupSlug
     *
     * @return Illuminate\Database\Eloquent\Collection|Friendship[]
     */
    public function getPendingFriendships($groupSlug = '')
    {
        return $this->findFriendships(Status::PENDING, $groupSlug)->get();
    }

    /**
     * Get all accepted friendships
     *
     * @param string $groupSlug
     *
     * @return Illuminate\Database\Eloquent\Collection|Friendship[]
     */
    public function getAcceptedFriendships($groupSlug = '')
    {
        return $this->findFriendships(Status::ACCEPTED, $groupSlug)->get();
    }

    /**
     * Get all denied friendship
     *
     * @return Illuminate\Database\Eloquent\Collection|Friendship[]
     */
    public function getDeniedFriendships()
    {
        return $this->findFriendships(Status::DENIED)->get();
    }

    /**
     * Get all blocked friendships inside
     *
     * @return void
     */
    public function getBlockedFriendships()
    {
        return $this->findFriendships(Status::BLOCKED)->get();
    }

    /**
     * Check if user is blocked
     *
     * @param Model $recipient
     *
     * @return boolean
     */
    public function hasBlocked(Model $recipient)
    {
        return $this->getSender()
            ->friends()
            ->whereRecipient($recipient)
            ->whereStatus(Status::BLOCKED)
            ->exists();
    }

    /**
     * Check if user is blocked by
     *
     * @param Model $recipient
     *
     * @return boolean
     */
    public function isBlockedBy(Model $recipient)
    {
        return $recipient->hasBlocked($this->getSender());
    }

    /**
     * Get all friendship requests
     *
     * @return Illuminate\Database\Eloquent\Collection|Friendship[]
     */
    public function getFriendRequests()
    {
        return Friendship::whereRecipient($this->getSender())
            ->whereStatus(Status::PENDING)
            ->with('sender')
            ->get();
    }

    /**
     * Count the friend requests
     *
     * @return integer
     */
    public function getFriendRequestsCount()
    {
        return $this->getFriendRequests()->count();
    }

    /**
     * This method will not return Friendship models
     * It will return the 'friends' models. ex: App\User
     *
     * @param integer $perPage
     * @param string $groupSlug
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getFriends($perPage = 0, $groupSlug = '')
    {
        return $this->getOrPaginate($this->getFriendsQueryBuilder($groupSlug), $perPage);
    }

    /**
     * This method will not return Friendship models
     * It will return the 'friends' models. ex: App\User
     *
     * @param Model   $other
     * @param integer $perPage
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getMutualFriends(Model $other, $perPage = 0)
    {
        return $this->getOrPaginate($this->getMutualFriendsQueryBuilder($other), $perPage);
    }

    /**
     * Get the number of mutual friends
     *
     * @param Model $other
     *
     * @return void
     */
    public function getMutualFriendsCount(Model $other)
    {
        return $this->getMutualFriendsQueryBuilder($other)->count();
    }

    /**
     * This method will not return Friendship models
     * It will return the 'friends' models. ex: App\User
     *
     * @param integer $perPage
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getFriendsOfFriends($perPage = 0)
    {
        return $this->getOrPaginate($this->friendsOfFriendsQueryBuilder(), $perPage);
    }

    /**
     * Get the number of friends
     *
     * @param string $groupSlug
     *
     * @return void
     */
    public function getFriendsCount($groupSlug = '')
    {
        return $this->findFriendships(Status::ACCEPTED, $groupSlug)->count();
    }

    /**
     * Check if users can be friends
     *
     * @param Model $recipient
     *
     * @return void
     */
    public function canBefriend(Model $recipient)
    {
        // if user has Blocked the recipient and changed his mind
        // he can send a friend request after unblocking
        if ($this->hasBlocked($recipient)) {
            $this->unblockFriend($recipient);
            return true;
        }

        // if sender has a friendship with the recipient return false
        if ($friendship = $this->getFriendship($recipient)) {
            // if previous friendship was Denied then let the user send fr
            if ($friendship->status != Status::DENIED) {
                return false;
            }
        }

        return true;
    }

    /**
     * Find the friendship between users
     *
     * @param Model $recipient
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    private function findFriendship(Model $recipient)
    {
        return Friendship::betweenModels($this->getSender(), $recipient);
    }

    /**
     * Find user friendships
     *
     * @param int    $status
     * @param string $groupSlug
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    private function findFriendships($status = null, $groupSlug = '')
    {
        $query = Friendship::where(
            function ($query) {
                $query->where(
                    function ($q) {
                        $q->whereSender($this->getSender());
                    }
                )->orWhere(
                    function ($q) {
                        $q->whereRecipient($this->getSender());
                    }
                );
            }
        )->whereGroup($this->getSender(), $groupSlug);

        // if $status is passed, add where clause
        if (!is_null($status)) {
            $query->where('status', $status);
        }

        return $query;
    }

    /**
     * Get the query builder of the 'friend' model
     *
     * @param string $groupSlug
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    private function getFriendsQueryBuilder($groupSlug = '')
    {
        $friendships = $this->findFriendships(Status::ACCEPTED, $groupSlug)
            ->get(['sender_id', 'recipient_id']);

        $recipients = $friendships->pluck('recipient_id')->all();
        $senders = $friendships->pluck('sender_id')->all();

        return $this->getSender()
            ->where('id', '!=', $this->getSender()->getKey())
            ->whereIn('id', array_merge($recipients, $senders));
    }

    /**
     * Get the query builder of the 'friend' model
     *
     * @param Model $other
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    private function getMutualFriendsQueryBuilder(Model $other)
    {
        $user1['friendships'] = $this->findFriendships(Status::ACCEPTED)
            ->get(['sender_id', 'recipient_id']);

        $user1['recipients'] = $user1['friendships']->pluck('recipient_id')->all();
        $user1['senders'] = $user1['friendships']->pluck('sender_id')->all();

        $user2['friendships'] = $other->findFriendships(Status::ACCEPTED)
            ->get(['sender_id', 'recipient_id']);

        $user2['recipients'] = $user2['friendships']->pluck('recipient_id')->all();
        $user2['senders'] = $user2['friendships']->pluck('sender_id')->all();

        $mutualFriendships = array_unique(
            array_intersect(
                array_merge($user1['recipients'], $user1['senders']),
                array_merge($user2['recipients'], $user2['senders'])
            )
        );

        return $this->whereNotIn(
            'id',
            [$this->getSender()->getKey(), $other->getKey()]
        )->whereIn('id', $mutualFriendships);
    }

    /**
     * Get the query builder for friendsOfFriends ('friend' model)
     *
     * @param string $groupSlug
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    private function friendsOfFriendsQueryBuilder($groupSlug = '')
    {
        $friendships = $this->findFriendships(Status::ACCEPTED)
            ->get(['sender_id', 'recipient_id']);

        $recipients = $friendships->pluck('recipient_id')->all();
        $senders = $friendships->pluck('sender_id')->all();

        $friendIds = array_unique(array_merge($recipients, $senders));

        $fofs = Friendship::where('status', Status::ACCEPTED)
            ->where(
                function ($query) use ($friendIds) {
                    $query->where(
                        function ($q) use ($friendIds) {
                            $q->whereIn('sender_id', $friendIds);
                        }
                    )->orWhere(
                        function ($q) use ($friendIds) {
                            $q->whereIn('recipient_id', $friendIds);
                        }
                    );
                }
            )
            ->whereGroup($this->getSender(), $groupSlug)
            ->get(['sender_id', 'recipient_id']);

        $fofIds = array_unique(
            array_merge(
                $fofs->pluck('sender_id')->all(),
                $fofs->pluck('recipient_id')->all()
            )
        );

        return $this->getSender()
            ->whereIn('id', $fofIds)
            ->whereNotIn('id', $friendIds);
    }

    protected function getOrPaginate($builder, $perPage)
    {
        if ($perPage == 0) {
            return $builder->get();
        }

        return $builder->paginate($perPage);
    }
}