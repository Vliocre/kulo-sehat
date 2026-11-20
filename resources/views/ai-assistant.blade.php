<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>AI Assistant · KuloSehat</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body{background:#f4f8f6}.assistant-shell{min-height:100dvh;background:radial-gradient(ellipse at 15% 0%,rgba(16,185,129,.09),transparent 35%),#f4f8f6}.chat-scroll{scrollbar-width:thin;scrollbar-color:#cbd5d1 transparent}.suggestion{transition:transform .2s,border-color .2s,box-shadow .2s}.suggestion:hover{transform:translateY(-2px);border-color:#6ee7b7;box-shadow:0 12px 28px rgba(15,118,110,.08)}.typing-dot{animation:typing 1s infinite ease-in-out}.typing-dot:nth-child(2){animation-delay:.15s}.typing-dot:nth-child(3){animation-delay:.3s}@keyframes typing{0%,60%,100%{transform:translateY(0);opacity:.45}30%{transform:translateY(-4px);opacity:1}}@media(prefers-reduced-motion:reduce){*,*::before,*::after{animation-duration:.01ms!important;transition-duration:.01ms!important}}
    </style>
</head>
<body class="assistant-shell min-h-screen font-sans antialiased text-slate-800">
    <div class="mx-auto flex min-h-screen max-w-6xl flex-col px-4 py-4 sm:px-6 sm:py-6">
        <header class="flex items-center justify-between rounded-2xl border border-white/80 bg-white/85 px-4 py-3 shadow-sm backdrop-blur sm:px-6">
            <a href="{{ auth()->user()->role === 'dokter' ? route('doctor.dashboard') : route('dashboard') }}" class="inline-flex items-center gap-3 text-sm font-semibold text-slate-600 transition hover:text-emerald-700" aria-label="Kembali ke dashboard">
                <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-emerald-50 text-emerald-700"><svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="m15 18-6-6 6-6M9 12h12" stroke-linecap="round" stroke-linejoin="round"/></svg></span>
                <span class="hidden sm:inline">Dashboard</span>
            </a>
            <a href="{{ auth()->user()->role === 'dokter' ? route('doctor.dashboard') : route('dashboard') }}" class="text-lg font-bold tracking-tight text-slate-900">Hela<span class="text-emerald-600">Vio.</span></a>
            <span class="inline-flex items-center gap-2 rounded-full bg-emerald-50 px-3 py-2 text-xs font-semibold text-emerald-700"><span class="h-2 w-2 rounded-full bg-emerald-500"></span> AI Assistant</span>
        </header>

        <main class="my-5 flex min-h-[calc(100dvh-8rem)] flex-1 flex-col overflow-hidden rounded-[28px] border border-slate-100 bg-white shadow-[0_20px_70px_rgba(15,23,42,.07)] sm:my-6">
            <div class="border-b border-slate-100 px-5 py-5 sm:px-8">
                <div class="flex items-center gap-4">
                    <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-gradient-to-br from-emerald-500 to-teal-700 text-white shadow-lg shadow-emerald-700/20"><svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"><path stroke-linecap="round" stroke-linejoin="round" d="m12 3 1.9 5.8L20 11l-6.1 2.2L12 19l-1.9-5.8L4 11l6.1-2.2L12 3ZM19 15l.9 2.1L22 18l-2.1.9L19 21l-.9-2.1L16 18l2.1-.9L19 15Z"/></svg></div>
                    <div><p class="text-xs font-bold uppercase tracking-[.18em] text-emerald-700">Asisten kesehatan</p><h1 class="mt-0.5 text-xl font-bold text-slate-900 sm:text-2xl">AI Assistant</h1><p class="mt-1 text-sm text-slate-500">Ruang untuk mencari informasi kesehatan dengan lebih mudah.</p></div>
                </div>
            </div>

            <section id="conversation" class="chat-scroll flex flex-1 flex-col overflow-y-auto px-4 py-8 sm:px-8" aria-live="polite">
                <div id="empty-state" class="m-auto w-full max-w-2xl py-8 text-center {{ ($conversation?->messages->isNotEmpty() ?? false) ? 'hidden' : '' }}">
                    <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-[22px] bg-emerald-50 text-emerald-700"><svg class="h-8 w-8" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"><path stroke-linecap="round" stroke-linejoin="round" d="m12 3 1.9 5.8L20 11l-6.1 2.2L12 19l-1.9-5.8L4 11l6.1-2.2L12 3ZM19 15l.9 2.1L22 18l-2.1.9L19 21l-.9-2.1L16 18l2.1-.9L19 15Z"/></svg></div>
                    <h2 class="mt-5 text-2xl font-bold tracking-tight text-slate-900 sm:text-3xl">Apa yang ingin Anda ketahui?</h2>
                    <p class="mx-auto mt-3 max-w-lg text-sm leading-6 text-slate-500">Periksa disini untuk deteksi penyakit luar.Tanyakan hal seputar kesehatan untuk menemukan informasi yang relevan.</p>
                    <div class="mt-8 grid gap-3 text-left sm:grid-cols-2">
                        @foreach(['Periksa penyakit kulit','Kapan sebaiknya saya memeriksakan kesehatan?','Bagaimana membaca informasi gizi?','Apa kebiasaan sederhana untuk menjaga kesehatan?'] as $prompt)
                            <button type="button" class="suggestion rounded-2xl border border-slate-200 bg-white p-4 text-sm font-medium leading-5 text-slate-700" data-prompt="{{ $prompt }}"><span class="mb-2 block text-emerald-600"><svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="m12 3 1.5 5.5L19 10l-5.5 1.5L12 17l-1.5-5.5L5 10l5.5-1.5L12 3Z" stroke-linejoin="round"/></svg></span>{{ $prompt }}</button>
                        @endforeach
                    </div>
                </div>
                <div id="messages" class="mx-auto w-full max-w-3xl space-y-5 {{ ($conversation?->messages->isNotEmpty() ?? false) ? '' : 'hidden' }}">
                    @foreach($conversation?->messages ?? [] as $savedMessage)
                        <div class="flex justify-end">
                            <div class="max-w-[88%] sm:max-w-[76%]">
                                <p class="mb-1 px-1 text-right text-[10px] font-semibold text-emerald-700">Anda</p>
                                <div class="rounded-2xl rounded-br-md bg-gradient-to-br from-emerald-600 to-teal-700 px-4 py-3 text-white shadow-sm">
                                    @if($savedMessage->content)<p class="whitespace-pre-line text-sm leading-6">{{ $savedMessage->content }}</p>@endif
                                    @foreach($savedMessage->images ?? [] as $imageIndex => $path)
                                        <a href="{{ route('ai-assistant.image', [$savedMessage, $imageIndex]) }}" target="_blank" rel="noopener" class="block">
                                            <img src="{{ route('ai-assistant.image', [$savedMessage, $imageIndex]) }}" alt="Gambar lampiran" loading="lazy" class="mt-2 max-h-72 rounded-xl object-contain">
                                        </a>
                                    @endforeach
                                    <p class="mt-1.5 text-right text-[10px] text-emerald-100">{{ $savedMessage->created_at->format('d M Y, H:i') }}</p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </section>

            <div class="border-t border-slate-100 bg-white px-4 pb-4 pt-4 sm:px-8 sm:pb-6">
                <div id="notice" class="mx-auto mb-3 hidden max-w-3xl rounded-xl border border-amber-200 bg-amber-50 px-4 py-3 text-sm leading-5 text-amber-800" role="status" aria-live="polite"></div>
                <form id="chat-form" class="mx-auto max-w-3xl" enctype="multipart/form-data">
                    <label for="message" class="sr-only">Tulis pertanyaan kesehatan</label>
                    <div id="image-preview" class="mx-auto mb-3 hidden max-w-3xl flex-wrap gap-3" aria-live="polite"></div>
                    <div class="flex items-end gap-2 rounded-2xl border border-slate-200 bg-slate-50 p-2 transition focus-within:border-emerald-400 focus-within:ring-4 focus-within:ring-emerald-50 sm:gap-3 sm:pl-3">
                        <label for="images" title="Lampirkan gambar" class="flex h-10 w-10 shrink-0 cursor-pointer items-center justify-center rounded-xl text-slate-500 transition hover:bg-white hover:text-emerald-700 focus-within:ring-2 focus-within:ring-emerald-300">
                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M15.172 7 8 14.172a2.828 2.828 0 1 0 4 4L20 10a5.657 5.657 0 0 0-8-8L4 10a8.485 8.485 0 0 0 12 12l6-6"/></svg>
                            <input id="images" name="images[]" type="file" accept="image/jpeg,image/png,image/webp,image/gif" multiple class="sr-only">
                        </label>
                        <textarea id="message" rows="1" maxlength="2000" placeholder="Tulis pertanyaan kesehatan Anda…" class="max-h-32 min-h-11 flex-1 resize-y border-0 bg-transparent py-3 text-sm leading-5 text-slate-800 placeholder:text-slate-400 focus:ring-0"></textarea>
                        <button id="send-button" type="submit" aria-label="Kirim pesan" class="mb-0.5 flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-emerald-600 text-white shadow-md shadow-emerald-700/20 transition hover:bg-emerald-700 focus:outline-none focus:ring-4 focus:ring-emerald-200 disabled:cursor-wait disabled:opacity-60"><svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="m5 12 14-7-4 14-3.5-5.5L5 12Z"/><path stroke-linecap="round" stroke-linejoin="round" d="m11.5 13.5 3-3"/></svg></button>
                    </div>
                    <p class="mt-3 text-center text-[11px] leading-4 text-slate-400">Informasi pada halaman ini tidak menggantikan pemeriksaan atau saran tenaga medis.</p>
                </form>
            </div>
        </main>
    </div>
    <script>
        const form=document.getElementById('chat-form'), input=document.getElementById('message'), empty=document.getElementById('empty-state'), messages=document.getElementById('messages'), notice=document.getElementById('notice'), fileInput=document.getElementById('images'), preview=document.getElementById('image-preview'), sendButton=document.getElementById('send-button'), csrf=document.querySelector('meta[name="csrf-token"]').content;
        let selectedImages=[];
        const showNotice=(text,error=false)=>{notice.textContent=text;notice.classList.remove('hidden','border-amber-200','bg-amber-50','text-amber-800','border-rose-200','bg-rose-50','text-rose-700');notice.classList.add(...(error?['border-rose-200','bg-rose-50','text-rose-700']:['border-amber-200','bg-amber-50','text-amber-800']))};
        const renderPreview=()=>{preview.querySelectorAll('img').forEach(image=>URL.revokeObjectURL(image.src));preview.replaceChildren();preview.classList.toggle('hidden',selectedImages.length===0);selectedImages.forEach((file,index)=>{const card=document.createElement('div');card.className='relative h-24 w-24 overflow-hidden rounded-xl border border-slate-200 bg-slate-100';const image=document.createElement('img');image.src=URL.createObjectURL(file);image.alt=file.name;image.className='h-full w-full object-cover';const remove=document.createElement('button');remove.type='button';remove.className='absolute right-1 top-1 flex h-6 w-6 items-center justify-center rounded-full bg-slate-900/75 text-sm text-white';remove.setAttribute('aria-label','Hapus '+file.name);remove.textContent='×';remove.addEventListener('click',()=>{selectedImages.splice(index,1);renderPreview()});card.append(image,remove);preview.append(card)})};
        fileInput.addEventListener('change',()=>{selectedImages.push(...Array.from(fileInput.files||[]));fileInput.value='';renderPreview()});
        document.querySelectorAll('[data-prompt]').forEach(button=>button.addEventListener('click',()=>{input.value=button.dataset.prompt;input.focus()}));
        const appendMessage=(message)=>{empty.classList.add('hidden');messages.classList.remove('hidden');const row=document.createElement('div');row.className='flex justify-end';const column=document.createElement('div');column.className='max-w-[88%] sm:max-w-[76%]';const label=document.createElement('p');label.className='mb-1 px-1 text-right text-[10px] font-semibold text-emerald-700';label.textContent='Anda';const bubble=document.createElement('div');bubble.className='rounded-2xl rounded-br-md bg-gradient-to-br from-emerald-600 to-teal-700 px-4 py-3 text-white shadow-sm';if(message.content){const text=document.createElement('p');text.className='whitespace-pre-line text-sm leading-6';text.textContent=message.content;bubble.append(text)}(message.images||[]).forEach((url)=>{const link=document.createElement('a');link.href=url;link.target='_blank';link.rel='noopener';const image=document.createElement('img');image.src=url;image.alt='Gambar lampiran';image.loading='lazy';image.className='mt-2 max-h-72 rounded-xl object-contain';link.append(image);bubble.append(link)});const time=document.createElement('p');time.className='mt-1.5 text-right text-[10px] text-emerald-100';time.textContent=message.created_at;bubble.append(time);column.append(label,bubble);row.append(column);messages.append(row);document.getElementById('conversation').scrollTop=document.getElementById('conversation').scrollHeight};
        form.addEventListener('submit',async(event)=>{event.preventDefault();const content=input.value.trim();if(!content&&!selectedImages.length)return;const body=new FormData();if(content)body.append('content',content);selectedImages.forEach(file=>body.append('images[]',file));sendButton.disabled=true;showNotice('Menyimpan pesan dan gambar…');try{const response=await fetch(@json(route('ai-assistant.messages.store')),{method:'POST',headers:{'X-CSRF-TOKEN':csrf,'Accept':'application/json'},body,credentials:'same-origin'});const result=await response.json();if(!response.ok)throw new Error(result.message||Object.values(result.errors||{}).flat().join(' ')||'Pesan gagal dikirim.');appendMessage(result.message);showNotice(result.notice||'Pesan berhasil disimpan.');input.value='';selectedImages=[];renderPreview()}catch(error){showNotice(error.message||'Terjadi kesalahan saat mengirim pesan.',true)}finally{sendButton.disabled=false;input.focus()}});
    </script>
</body>
</html>
