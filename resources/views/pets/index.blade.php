<div class="container">
    <div class="row">
        @foreach($pets as $pet)
            <!-- conteúdo dos cards de pet -->
        @endforeach
    </div>
    <div class="d-flex justify-content-center mt-4">
        {{ $pets->links() }}
    </div>
</div>
