<div style="background:#fff;width:90%;margin:0 auto;border:1px solid #ccc;color:#8B8B8B;padding:5px 10px;" id="zetth-email">
    <center>
      <a href="{{ getSiteURL('/') }}">
        <img src="{{ getImageLogo($site->logo) }}" style="max-height:200px;">
      </a>
    </center>
    <hr>
    <p>Halo,</p>
    <p>Anda baru saja mendapatkan balasan komentar dari artikel "{{ $post->title }}". Klik tautan di bawah untuk langsung membacanya.</p>
    <p>
      <a href="{{ getSiteURL(env('SINGLE_POST_PATH', 'post') . '/' . $post->slug . '#komentar-' . md5($comment->id)) }}" style="padding:5px;border:1px solid transparent;color:#fff;background:#ed4568;border-radius:4px;text-decoration:none;">Lihat komentar</a>
    </p>
    <hr>
    <p><small>*mohon untuk tidak membalas email ini.</small></p>
  </div>