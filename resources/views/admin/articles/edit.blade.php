<form method="POST" action="{{ route('articles.update', $article->_id) }}">
@csrf
@method('PUT')

<input name="judul" value="{{ $article->judul }}">
<input name="kategori" value="{{ $article->kategori }}">
<input name="ringkasan" value="{{ $article->ringkasan }}">

<textarea name="isi">{{ $article->isi }}</textarea>

<button>Update</button>
</form>
