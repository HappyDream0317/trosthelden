<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    @include('documents.components.style')
</head>
<body>
<header>
    @include('documents.components.logo')
    @include('documents.components.separator', ['num' => 3])
</header>
<footer>
    @include('documents.components.separator', ['num' => 3])
    @include('documents.components.footer')
</footer>
<main>

    @include('documents.components.title')

    @include('documents.components.quote')

    @include('documents.components.intro', ['product' => $product])

    @include('documents.components.code', ['code' => $code])

    <div class="page_break"></div>

    @include('documents.components.instructions', [
        'product' => $product
    ])

</main>

</body>
</html>