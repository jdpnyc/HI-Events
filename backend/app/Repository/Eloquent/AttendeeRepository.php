<?php

namespace HiEvents\Repository\Eloquent;

use HiEvents\DomainObjects\AttendeeCheckInDomainObject;
use HiEvents\DomainObjects\AttendeeDomainObject;
use HiEvents\DomainObjects\Generated\AttendeeDomainObjectAbstract;
use HiEvents\DomainObjects\Status\OrderStatus;
use HiEvents\Http\DTO\QueryParamsDTO;
use HiEvents\Models\Attendee;
use HiEvents\Repository\Eloquent\Value\Relationship;
use HiEvents\Repository\Interfaces\AttendeeRepositoryInterface;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class AttendeeRepository extends BaseRepository implements AttendeeRepositoryInterface
{
    protected function getModel(): string
    {
        return Attendee::class;
    }

    public function getDomainObject(): string
    {
        return AttendeeDomainObject::class;
    }

    public function findByEventIdForExport(int $eventId): Collection
    {
        $this->applyConditions([
            'event_id' => $eventId,
        ]);
        $model = $this->model->limit(10000)->get();
        $this->resetModel();

        return $this->handleResults($model);
    }


    public function findByEventId(int $eventId, QueryParamsDTO $params): LengthAwarePaginator
    {
        $where = [
            ['attendees.event_id', '=', $eventId]
        ];

        if ($params->query) {
            $where[] = static function (Builder $builder) use ($params) {
                $builder
                    ->where(
                        DB::raw(
                            sprintf(
                                "(%s||' '||%s)",
                                'attendees.' . AttendeeDomainObjectAbstract::FIRST_NAME,
                                'attendees.' . AttendeeDomainObjectAbstract::LAST_NAME,
                            )
                        ), 'ilike', '%' . $params->query . '%')
                    ->orWhere('attendees.' . AttendeeDomainObjectAbstract::LAST_NAME, 'ilike', '%' . $params->query . '%')
                    ->orWhere('attendees.' . AttendeeDomainObjectAbstract::FIRST_NAME, 'ilike', '%' . $params->query . '%')
                    ->orWhere('attendees.' . AttendeeDomainObjectAbstract::PUBLIC_ID, 'ilike', '%' . $params->query . '%')
                    ->orWhere('attendees.' . AttendeeDomainObjectAbstract::EMAIL, 'ilike', '%' . $params->query . '%');
            };
        }

        if ($params->filter_fields) {
            foreach ($params->filter_fields as $field => $value) {
                if (in_array($field, AttendeeDomainObject::getAllowedFilterFields(), true) === false) {
                    continue;
                }
                if ($value === null || $value === '' || $value === []) {
                    continue;
                }
                $this->model
                    ->whereIn('attendees.' . $field, array_map('trim', explode(',', $value)));
            }
        }

        $this->model = $this->model->select('attendees.*')
            ->join('orders', 'orders.id', '=', 'attendees.order_id')
            ->whereIn('orders.status', [OrderStatus::COMPLETED->name, OrderStatus::CANCELLED->name])
            ->orderBy(
                'attendees.' . ($params->sort_by ?? AttendeeDomainObject::getDefaultSort()),
                $params->sort_direction ?? 'desc',
            );

        return $this->paginateWhere(
            where: $where,
            limit: $params->per_page,
            page: $params->page,
        );
    }

    public function getAttendeesByCheckInShortId(string $shortId, QueryParamsDTO $params): Paginator
    {
        $where = [];
        if ($params->query) {
            $where[] = static function (Builder $builder) use ($params) {
                $builder
                    ->where(
                        DB::raw(
                            sprintf(
                                "(%s||' '||%s)",
                                'attendees.' . AttendeeDomainObjectAbstract::FIRST_NAME,
                                'attendees.' . AttendeeDomainObjectAbstract::LAST_NAME,
                            )
                        ), 'ilike', '%' . $params->query . '%')
                    ->orWhere('attendees.' . AttendeeDomainObjectAbstract::LAST_NAME, 'ilike', '%' . $params->query . '%')
                    ->orWhere('attendees.' . AttendeeDomainObjectAbstract::FIRST_NAME, 'ilike', '%' . $params->query . '%')
                    ->orWhere('attendees.' . AttendeeDomainObjectAbstract::PUBLIC_ID, 'ilike', '%' . $params->query . '%')
                    ->orWhere('attendees.' . AttendeeDomainObjectAbstract::EMAIL, 'ilike', '%' . $params->query . '%');
            };
        }

        $this->model = $this->model->select('attendees.*')
            ->join('orders', 'orders.id', '=', 'attendees.order_id')
            ->join('ticket_check_in_lists', 'ticket_check_in_lists.ticket_id', '=', 'attendees.ticket_id')
            ->join('check_in_lists', 'check_in_lists.id', '=', 'ticket_check_in_lists.check_in_list_id')
            ->where('check_in_lists.short_id', $shortId)
            ->whereIn('orders.status', [OrderStatus::COMPLETED->name]);

        $this->loadRelation(new Relationship(AttendeeCheckInDomainObject::class, name: 'check_in'));

        return $this->simplePaginateWhere(
            where: $where,
            limit: 100,
        );
    }
}
