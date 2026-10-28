<?php
require_once __DIR__ . "/../repositories/book-repository.php";
require_once __DIR__ . "/../repositories/category-repository.php";
require_once __DIR__ . "/../repositories/author-repository.php";
function getBookRequest($request)
{
    return [
        'id' => trim(htmlspecialchars($_REQUEST['id'])),
        'title' => trim(htmlspecialchars($_REQUEST['title'])),
        'isbn' => trim(htmlspecialchars($_REQUEST['isbn'])),
        'year' => trim(htmlspecialchars($_REQUEST['year'])),
        'stock' => trim(htmlspecialchars($_REQUEST['stock'])),
        'description' => trim(htmlspecialchars($_REQUEST['description'])),
        'category_id' => trim(htmlspecialchars($_REQUEST['category_id'])),
        'author_ids' => $_REQUEST['author_ids'] ?? [],
    ];
}

function validateBook($request, $ignoreBookId = 0)
{
    $errors = [];

    //validate title: required max characther 100
    if($request['title'] == '') {
        $errors['title'] = 'Judul Buku Wajib Diisi';
    } elseif(mb_strlen($request['title']) > 100) {
        $errors['title'] = 'Judul Buku Tidak Boleh Melebihi 100 Karakter';
    }

    //validated isbn required , isTaken?
    if($request['isbn'] == '') {
        $errors['isbn'] = 'ISBN Wajib Diisi';
    }elseif(isIsbnTaken($request['isbn'], $ignoreBookId)){
        $errors['isbn'] = 'ISBN sudah terdaftar sebelumnya';
    }

    // validate year:required, isNumber
    if($request['year'] == '') {
        $errors['year'] = 'Tahun Wajib Diisi';
    } elseif(!is_int(intval($request['year']))) {
        $errors['year'] = 'Tahun wajib diisi menggunakan angka';
    }

    // validate stock:required, isNumber
    if($request['stock'] == '') {
        $errors['stock'] = 'Stock Wajib Diisi';
    } elseif(!is_int(intval($request['stock']))) {
        $errors['stock'] = 'Stock wajib diisi menggunakan angka';
    }

    // validated category: required, isExits
    if($request['category_id'] == '') {
        $errors['category_id'] = 'Kategory Wajib Dipilih';
    }elseif(!isCategoryExits($request['category_id'])) {
        $errors['category_id'] = 'Kategori yang dipilih tidak valid';
    }

    // validated description: required, max 200
    if($request['description'] == '') {
        $errors['description'] = 'Deskripsi Buku Wajib Diisi';
    } elseif(mb_strlen($request['description']) > 200) {
        $errors['description'] = 'Deskripsi Tidak Boleh Melebihi 100 Karakter';
    }

    //validate Authors: required, isExits
    if(count($request['author_ids']) < 1) 
    {
        $errors['author_ids'] = 'Penulis buku wajib dipilih';
    } else{
        $authors = getAuthors();
        $validAuthorIds = array_column($authors,'id');
        
        foreach($request['author_ids'] as $authorId) {
            if(!in_array($authorId, $validAuthorIds)){
                $errors['author_ids'] = 'Penulis buku yang dipilih tidak valid';
            }
        }
    }

    return $errors;
}