<?php

// namespace App\Model;

// use Doctrine\ORM\QueryBuilder;
// use Doctrine\ORM\Tools\Pagination\OffsetPaginator;
// use Doctrine\ORM\Tools\Pagination\Window;
// use Symfony\Component\Serializer\Attribute\Groups;

// class Paginator
// {
//     #[Groups("paginate")]
//     public int $total;

//     #[Groups("paginate")]
//     public int $lastPage;

//     #[Groups("paginate")]
//     public $items;

//     public function paginate(QueryBuilder $query, PaginationDTO $paginationDTO): self
//     {
//         $paginator = (new OffsetPaginator(fetchJoinCollection: true))->paginate(
//             $query,
//             Window::fromPageNumberAndSize($paginationDTO->page, $paginationDTO->limit),
//         );

//         $this->total = $paginator->count();
//         $this->lastPage = $paginator->getPageCount();
//         $this->items = $paginator->getItems();

//         return $this;
//     }
// }