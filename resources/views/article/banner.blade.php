<div class="article banner container">
    <div class="article banner head">
        <div class="article banner date">{{ $textService->formatDate($date) }}</div>
        <h1 class="article banner title">{{ $title }}</h1>
    </div>
    <div class="article banner image">
        <img src={{ $banner }}>
        <div class="article banner photo-author">{{ $photoAuthor }}</div>
    </div>
</div>
