<?php

namespace HiEvents\DomainObjects\Generated;

/**
 * THIS FILE IS AUTOGENERATED - DO NOT EDIT IT DIRECTLY.
 * @package HiEvents\DomainObjects\Generated
 */
abstract class AttendeeCheckInDomainObjectAbstract extends \HiEvents\DomainObjects\AbstractDomainObject
{
    final public const SINGULAR_NAME = 'attendee_check_in';
    final public const PLURAL_NAME = 'attendee_check_ins';
    final public const ID = 'id';
    final public const CHECK_IN_LIST_ID = 'check_in_list_id';
    final public const TICKET_ID = 'ticket_id';
    final public const ATTENDEE_ID = 'attendee_id';
    final public const SHORT_ID = 'short_id';
    final public const IP_ADDRESS = 'ip_address';
    final public const DELETED_AT = 'deleted_at';
    final public const CREATED_AT = 'created_at';
    final public const UPDATED_AT = 'updated_at';

    protected int $id;
    protected int $check_in_list_id;
    protected int $ticket_id;
    protected int $attendee_id;
    protected string $short_id;
    protected string $ip_address;
    protected ?string $deleted_at = null;
    protected ?string $created_at = null;
    protected ?string $updated_at = null;

    public function toArray(): array
    {
        return [
                    'id' => $this->id ?? null,
                    'check_in_list_id' => $this->check_in_list_id ?? null,
                    'ticket_id' => $this->ticket_id ?? null,
                    'attendee_id' => $this->attendee_id ?? null,
                    'short_id' => $this->short_id ?? null,
                    'ip_address' => $this->ip_address ?? null,
                    'deleted_at' => $this->deleted_at ?? null,
                    'created_at' => $this->created_at ?? null,
                    'updated_at' => $this->updated_at ?? null,
                ];
    }

    public function setId(int $id): self
    {
        $this->id = $id;
        return $this;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setCheckInListId(int $check_in_list_id): self
    {
        $this->check_in_list_id = $check_in_list_id;
        return $this;
    }

    public function getCheckInListId(): int
    {
        return $this->check_in_list_id;
    }

    public function setTicketId(int $ticket_id): self
    {
        $this->ticket_id = $ticket_id;
        return $this;
    }

    public function getTicketId(): int
    {
        return $this->ticket_id;
    }

    public function setAttendeeId(int $attendee_id): self
    {
        $this->attendee_id = $attendee_id;
        return $this;
    }

    public function getAttendeeId(): int
    {
        return $this->attendee_id;
    }

    public function setShortId(string $short_id): self
    {
        $this->short_id = $short_id;
        return $this;
    }

    public function getShortId(): string
    {
        return $this->short_id;
    }

    public function setIpAddress(string $ip_address): self
    {
        $this->ip_address = $ip_address;
        return $this;
    }

    public function getIpAddress(): string
    {
        return $this->ip_address;
    }

    public function setDeletedAt(?string $deleted_at): self
    {
        $this->deleted_at = $deleted_at;
        return $this;
    }

    public function getDeletedAt(): ?string
    {
        return $this->deleted_at;
    }

    public function setCreatedAt(?string $created_at): self
    {
        $this->created_at = $created_at;
        return $this;
    }

    public function getCreatedAt(): ?string
    {
        return $this->created_at;
    }

    public function setUpdatedAt(?string $updated_at): self
    {
        $this->updated_at = $updated_at;
        return $this;
    }

    public function getUpdatedAt(): ?string
    {
        return $this->updated_at;
    }
}
