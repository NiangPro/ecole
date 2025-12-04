@extends('layouts.app')

@section('title', trans('app.auth.register.title') . ' | NiangProgrammeur')

@section('styles')
<style>
    .auth-container {
        min-height: calc(100vh - 200px);
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 40px 20px;
        background: linear-gradient(135deg, #0f172a 0%, #1e293b 50%, #0f172a 100%);
    }
    
    body:not(.dark-mode) .auth-container {
        background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 50%, #e2e8f0 100%);
    }
    
    .auth-card {
        max-width: 500px;
        width: 100%;
        background: rgba(15, 23, 42, 0.8);
        backdrop-filter: blur(20px);
        border: 2px solid rgba(6, 182, 212, 0.3);
        border-radius: 24px;
        padding: 40px;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.5);
        transition: all 0.3s ease;
    }
    
    body:not(.dark-mode) .auth-card {
        background: rgba(255, 255, 255, 0.95);
        border-color: rgba(6, 182, 212, 0.25);
        box-shadow: 0 20px 60px rgba(6, 182, 212, 0.15);
    }
    
    .auth-title {
        font-size: 2rem;
        font-weight: 900;
        color: #fff;
        margin-bottom: 10px;
        text-align: center;
        background: linear-gradient(135deg, #06b6d4, #14b8a6);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }
    
    body:not(.dark-mode) .auth-title {
        color: rgba(30, 41, 59, 0.95);
    }
    
    .auth-subtitle {
        text-align: center;
        color: rgba(255, 255, 255, 0.6);
        margin-bottom: 30px;
    }
    
    body:not(.dark-mode) .auth-subtitle {
        color: rgba(30, 41, 59, 0.7);
    }
    
    .auth-label {
        display: block;
        color: rgba(255, 255, 255, 0.9);
        font-weight: 600;
        margin-bottom: 8px;
    }
    
    body:not(.dark-mode) .auth-label {
        color: rgba(30, 41, 59, 0.9);
    }
    
    .auth-input {
        width: 100%;
        padding: 12px 15px;
        background: rgba(15, 23, 42, 0.8);
        border: 1px solid rgba(6, 182, 212, 0.3);
        border-radius: 12px;
        color: #fff;
        font-size: 1rem;
        transition: all 0.3s ease;
    }
    
    body:not(.dark-mode) .auth-input {
        background: rgba(248, 250, 252, 0.9);
        border-color: rgba(6, 182, 212, 0.25);
        color: rgba(30, 41, 59, 0.9);
    }
    
    .auth-input:focus {
        outline: none;
        border-color: rgba(6, 182, 212, 0.6);
        box-shadow: 0 0 0 3px rgba(6, 182, 212, 0.1);
    }
    
    .auth-input::placeholder {
        color: rgba(255, 255, 255, 0.5);
    }
    
    body:not(.dark-mode) .auth-input::placeholder {
        color: rgba(30, 41, 59, 0.5);
    }
    
    .auth-button {
        width: 100%;
        padding: 14px;
        background: linear-gradient(135deg, #06b6d4, #14b8a6);
        color: #fff;
        border: none;
        border-radius: 12px;
        font-weight: 700;
        font-size: 1rem;
        cursor: pointer;
        transition: all 0.3s ease;
        margin-bottom: 20px;
    }
    
    .auth-button:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(6, 182, 212, 0.4);
    }
    
    .auth-link-text {
        text-align: center;
        padding-top: 20px;
        border-top: 1px solid rgba(6, 182, 212, 0.2);
    }
    
    .auth-link-text p {
        color: rgba(255, 255, 255, 0.6);
        margin-bottom: 15px;
    }
    
    body:not(.dark-mode) .auth-link-text p {
        color: rgba(30, 41, 59, 0.7);
    }
    
    .auth-link-button {
        display: inline-block;
        padding: 12px 30px;
        background: rgba(15, 23, 42, 0.8);
        color: #06b6d4;
        border: 2px solid rgba(6, 182, 212, 0.3);
        border-radius: 12px;
        text-decoration: none;
        font-weight: 600;
        transition: all 0.3s ease;
    }
    
    body:not(.dark-mode) .auth-link-button {
        background: rgba(248, 250, 252, 0.9);
        border-color: rgba(6, 182, 212, 0.25);
    }
    
    .auth-link-button:hover {
        transform: translateY(-2px);
        border-color: rgba(6, 182, 212, 0.5);
        box-shadow: 0 4px 12px rgba(6, 182, 212, 0.2);
    }
    
    .auth-alert {
        padding: 15px;
        border-radius: 12px;
        margin-bottom: 20px;
    }
    
    .auth-alert-error {
        background: rgba(239, 68, 68, 0.1);
        border: 1px solid rgba(239, 68, 68, 0.3);
        color: #ef4444;
    }
</style>
@endsection

@section('content')
<div class="auth-container">
    <div class="auth-card">
        <h1 class="auth-title">{{ trans('app.auth.register.title') }}</h1>
        <p class="auth-subtitle">{{ trans('app.auth.register.subtitle') }}</p>

        @if($errors->any())
            <div class="auth-alert auth-alert-error">
                <ul style="margin: 0; padding-left: 20px;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('register.post') }}" method="POST">
            @csrf
            
            <div style="margin-bottom: 20px;">
                <label class="auth-label">{{ trans('app.auth.register.full_name') }}</label>
                <input type="text" name="name" value="{{ old('name') }}" required 
                       class="auth-input"
                       placeholder="{{ trans('app.auth.register.full_name') }}">
            </div>
            
            <div style="margin-bottom: 20px;">
                <label class="auth-label">{{ trans('app.auth.register.email') }}</label>
                <input type="email" name="email" value="{{ old('email') }}" required 
                       class="auth-input"
                       placeholder="votre@email.com">
            </div>
            
            <div style="margin-bottom: 20px;">
                <label class="auth-label">{{ trans('app.auth.register.phone') }}</label>
                <div style="display: flex; gap: 10px;">
                    <select name="phone_country" id="phone_country" class="auth-input" style="width: 120px; flex-shrink: 0;">
                        <option value="+221" data-flag="🇸🇳">🇸🇳 +221</option>
                        <option value="+33" data-flag="🇫🇷">🇫🇷 +33</option>
                        <option value="+1" data-flag="🇺🇸">🇺🇸 +1</option>
                        <option value="+44" data-flag="🇬🇧">🇬🇧 +44</option>
                        <option value="+212" data-flag="🇲🇦">🇲🇦 +212</option>
                        <option value="+225" data-flag="🇨🇮">🇨🇮 +225</option>
                        <option value="+226" data-flag="🇧🇫">🇧🇫 +226</option>
                        <option value="+227" data-flag="🇳🇪">🇳🇪 +227</option>
                        <option value="+228" data-flag="🇹🇬">🇹🇬 +228</option>
                        <option value="+229" data-flag="🇧🇯">🇧🇯 +229</option>
                        <option value="+230" data-flag="🇲🇺">🇲🇺 +230</option>
                        <option value="+231" data-flag="🇱🇷">🇱🇷 +231</option>
                        <option value="+232" data-flag="🇸🇱">🇸🇱 +232</option>
                        <option value="+233" data-flag="🇬🇭">🇬🇭 +233</option>
                        <option value="+234" data-flag="🇳🇬">🇳🇬 +234</option>
                        <option value="+235" data-flag="🇹🇩">🇹🇩 +235</option>
                        <option value="+236" data-flag="🇨🇫">🇨🇫 +236</option>
                        <option value="+237" data-flag="🇨🇲">🇨🇲 +237</option>
                        <option value="+238" data-flag="🇨🇻">🇨🇻 +238</option>
                        <option value="+239" data-flag="🇸🇹">🇸🇹 +239</option>
                        <option value="+240" data-flag="🇬🇶">🇬🇶 +240</option>
                        <option value="+241" data-flag="🇬🇦">🇬🇦 +241</option>
                        <option value="+242" data-flag="🇨🇬">🇨🇬 +242</option>
                        <option value="+243" data-flag="🇨🇩">🇨🇩 +243</option>
                        <option value="+244" data-flag="🇦🇴">🇦🇴 +244</option>
                        <option value="+245" data-flag="🇬🇼">🇬🇼 +245</option>
                        <option value="+246" data-flag="🇮🇴">🇮🇴 +246</option>
                        <option value="+248" data-flag="🇸🇨">🇸🇨 +248</option>
                        <option value="+249" data-flag="🇸🇩">🇸🇩 +249</option>
                        <option value="+250" data-flag="🇷🇼">🇷🇼 +250</option>
                        <option value="+251" data-flag="🇪🇹">🇪🇹 +251</option>
                        <option value="+252" data-flag="🇸🇴">🇸🇴 +252</option>
                        <option value="+253" data-flag="🇩🇯">🇩🇯 +253</option>
                        <option value="+254" data-flag="🇰🇪">🇰🇪 +254</option>
                        <option value="+255" data-flag="🇹🇿">🇹🇿 +255</option>
                        <option value="+256" data-flag="🇺🇬">🇺🇬 +256</option>
                        <option value="+257" data-flag="🇧🇮">🇧🇮 +257</option>
                        <option value="+258" data-flag="🇲🇿">🇲🇿 +258</option>
                        <option value="+260" data-flag="🇿🇲">🇿🇲 +260</option>
                        <option value="+261" data-flag="🇲🇬">🇲🇬 +261</option>
                        <option value="+262" data-flag="🇷🇪">🇷🇪 +262</option>
                        <option value="+263" data-flag="🇿🇼">🇿🇼 +263</option>
                        <option value="+264" data-flag="🇳🇦">🇳🇦 +264</option>
                        <option value="+265" data-flag="🇲🇼">🇲🇼 +265</option>
                        <option value="+266" data-flag="🇱🇸">🇱🇸 +266</option>
                        <option value="+267" data-flag="🇧🇼">🇧🇼 +267</option>
                        <option value="+268" data-flag="🇸🇿">🇸🇿 +268</option>
                        <option value="+269" data-flag="🇰🇲">🇰🇲 +269</option>
                        <option value="+27" data-flag="🇿🇦">🇿🇦 +27</option>
                        <option value="+32" data-flag="🇧🇪">🇧🇪 +32</option>
                        <option value="+34" data-flag="🇪🇸">🇪🇸 +34</option>
                        <option value="+39" data-flag="🇮🇹">🇮🇹 +39</option>
                        <option value="+49" data-flag="🇩🇪">🇩🇪 +49</option>
                        <option value="+7" data-flag="🇷🇺">🇷🇺 +7</option>
                        <option value="+81" data-flag="🇯🇵">🇯🇵 +81</option>
                        <option value="+82" data-flag="🇰🇷">🇰🇷 +82</option>
                        <option value="+86" data-flag="🇨🇳">🇨🇳 +86</option>
                        <option value="+90" data-flag="🇹🇷">🇹🇷 +90</option>
                        <option value="+91" data-flag="🇮🇳">🇮🇳 +91</option>
                        <option value="+92" data-flag="🇵🇰">🇵🇰 +92</option>
                        <option value="+213" data-flag="🇩🇿">🇩🇿 +213</option>
                        <option value="+216" data-flag="🇹🇳">🇹🇳 +216</option>
                        <option value="+218" data-flag="🇱🇾">🇱🇾 +218</option>
                        <option value="+220" data-flag="🇬🇲">🇬🇲 +220</option>
                        <option value="+223" data-flag="🇲🇱">🇲🇱 +223</option>
                        <option value="+224" data-flag="🇬🇳">🇬🇳 +224</option>
                        <option value="+235" data-flag="🇹🇩">🇹🇩 +235</option>
                        <option value="+236" data-flag="🇨🇫">🇨🇫 +236</option>
                        <option value="+237" data-flag="🇨🇲">🇨🇲 +237</option>
                        <option value="+238" data-flag="🇨🇻">🇨🇻 +238</option>
                        <option value="+239" data-flag="🇸🇹">🇸🇹 +239</option>
                        <option value="+240" data-flag="🇬🇶">🇬🇶 +240</option>
                        <option value="+241" data-flag="🇬🇦">🇬🇦 +241</option>
                        <option value="+242" data-flag="🇨🇬">🇨🇬 +242</option>
                        <option value="+243" data-flag="🇨🇩">🇨🇩 +243</option>
                        <option value="+244" data-flag="🇦🇴">🇦🇴 +244</option>
                        <option value="+245" data-flag="🇬🇼">🇬🇼 +245</option>
                        <option value="+246" data-flag="🇮🇴">🇮🇴 +246</option>
                        <option value="+248" data-flag="🇸🇨">🇸🇨 +248</option>
                        <option value="+249" data-flag="🇸🇩">🇸🇩 +249</option>
                        <option value="+250" data-flag="🇷🇼">🇷🇼 +250</option>
                        <option value="+251" data-flag="🇪🇹">🇪🇹 +251</option>
                        <option value="+252" data-flag="🇸🇴">🇸🇴 +252</option>
                        <option value="+253" data-flag="🇩🇯">🇩🇯 +253</option>
                        <option value="+254" data-flag="🇰🇪">🇰🇪 +254</option>
                        <option value="+255" data-flag="🇹🇿">🇹🇿 +255</option>
                        <option value="+256" data-flag="🇺🇬">🇺🇬 +256</option>
                        <option value="+257" data-flag="🇧🇮">🇧🇮 +257</option>
                        <option value="+258" data-flag="🇲🇿">🇲🇿 +258</option>
                        <option value="+260" data-flag="🇿🇲">🇿🇲 +260</option>
                        <option value="+261" data-flag="🇲🇬">🇲🇬 +261</option>
                        <option value="+262" data-flag="🇷🇪">🇷🇪 +262</option>
                        <option value="+263" data-flag="🇿🇼">🇿🇼 +263</option>
                        <option value="+264" data-flag="🇳🇦">🇳🇦 +264</option>
                        <option value="+265" data-flag="🇲🇼">🇲🇼 +265</option>
                        <option value="+266" data-flag="🇱🇸">🇱🇸 +266</option>
                        <option value="+267" data-flag="🇧🇼">🇧🇼 +267</option>
                        <option value="+268" data-flag="🇸🇿">🇸🇿 +268</option>
                        <option value="+269" data-flag="🇰🇲">🇰🇲 +269</option>
                        <option value="+290" data-flag="🇸🇭">🇸🇭 +290</option>
                        <option value="+291" data-flag="🇪🇷">🇪🇷 +291</option>
                        <option value="+297" data-flag="🇦🇼">🇦🇼 +297</option>
                        <option value="+298" data-flag="🇫🇴">🇫🇴 +298</option>
                        <option value="+299" data-flag="🇬🇱">🇬🇱 +299</option>
                        <option value="+350" data-flag="🇬🇮">🇬🇮 +350</option>
                        <option value="+351" data-flag="🇵🇹">🇵🇹 +351</option>
                        <option value="+352" data-flag="🇱🇺">🇱🇺 +352</option>
                        <option value="+353" data-flag="🇮🇪">🇮🇪 +353</option>
                        <option value="+354" data-flag="🇮🇸">🇮🇸 +354</option>
                        <option value="+355" data-flag="🇦🇱">🇦🇱 +355</option>
                        <option value="+356" data-flag="🇲🇹">🇲🇹 +356</option>
                        <option value="+357" data-flag="🇨🇾">🇨🇾 +357</option>
                        <option value="+358" data-flag="🇫🇮">🇫🇮 +358</option>
                        <option value="+359" data-flag="🇧🇬">🇧🇬 +359</option>
                        <option value="+36" data-flag="🇭🇺">🇭🇺 +36</option>
                        <option value="+370" data-flag="🇱🇹">🇱🇹 +370</option>
                        <option value="+371" data-flag="🇱🇻">🇱🇻 +371</option>
                        <option value="+372" data-flag="🇪🇪">🇪🇪 +372</option>
                        <option value="+373" data-flag="🇲🇩">🇲🇩 +373</option>
                        <option value="+374" data-flag="🇦🇲">🇦🇲 +374</option>
                        <option value="+375" data-flag="🇧🇾">🇧🇾 +375</option>
                        <option value="+376" data-flag="🇦🇩">🇦🇩 +376</option>
                        <option value="+377" data-flag="🇲🇨">🇲🇨 +377</option>
                        <option value="+378" data-flag="🇸🇲">🇸🇲 +378</option>
                        <option value="+380" data-flag="🇺🇦">🇺🇦 +380</option>
                        <option value="+381" data-flag="🇷🇸">🇷🇸 +381</option>
                        <option value="+382" data-flag="🇲🇪">🇲🇪 +382</option>
                        <option value="+383" data-flag="🇽🇰">🇽🇰 +383</option>
                        <option value="+385" data-flag="🇭🇷">🇭🇷 +385</option>
                        <option value="+386" data-flag="🇸🇮">🇸🇮 +386</option>
                        <option value="+387" data-flag="🇧🇦">🇧🇦 +387</option>
                        <option value="+389" data-flag="🇲🇰">🇲🇰 +389</option>
                        <option value="+420" data-flag="🇨🇿">🇨🇿 +420</option>
                        <option value="+421" data-flag="🇸🇰">🇸🇰 +421</option>
                        <option value="+423" data-flag="🇱🇮">🇱🇮 +423</option>
                        <option value="+500" data-flag="🇫🇰">🇫🇰 +500</option>
                        <option value="+501" data-flag="🇧🇿">🇧🇿 +501</option>
                        <option value="+502" data-flag="🇬🇹">🇬🇹 +502</option>
                        <option value="+503" data-flag="🇸🇻">🇸🇻 +503</option>
                        <option value="+504" data-flag="🇭🇳">🇭🇳 +504</option>
                        <option value="+505" data-flag="🇳🇮">🇳🇮 +505</option>
                        <option value="+506" data-flag="🇨🇷">🇨🇷 +506</option>
                        <option value="+507" data-flag="🇵🇦">🇵🇦 +507</option>
                        <option value="+508" data-flag="🇵🇲">🇵🇲 +508</option>
                        <option value="+509" data-flag="🇭🇹">🇭🇹 +509</option>
                        <option value="+590" data-flag="🇬🇵">🇬🇵 +590</option>
                        <option value="+591" data-flag="🇧🇴">🇧🇴 +591</option>
                        <option value="+592" data-flag="🇬🇾">🇬🇾 +592</option>
                        <option value="+593" data-flag="🇪🇨">🇪🇨 +593</option>
                        <option value="+594" data-flag="🇬🇫">🇬🇫 +594</option>
                        <option value="+595" data-flag="🇵🇾">🇵🇾 +595</option>
                        <option value="+596" data-flag="🇲🇶">🇲🇶 +596</option>
                        <option value="+597" data-flag="🇸🇷">🇸🇷 +597</option>
                        <option value="+598" data-flag="🇺🇾">🇺🇾 +598</option>
                        <option value="+599" data-flag="🇧🇶">🇧🇶 +599</option>
                        <option value="+670" data-flag="🇹🇱">🇹🇱 +670</option>
                        <option value="+672" data-flag="🇦🇶">🇦🇶 +672</option>
                        <option value="+673" data-flag="🇧🇳">🇧🇳 +673</option>
                        <option value="+674" data-flag="🇳🇷">🇳🇷 +674</option>
                        <option value="+675" data-flag="🇵🇬">🇵🇬 +675</option>
                        <option value="+676" data-flag="🇹🇴">🇹🇴 +676</option>
                        <option value="+677" data-flag="🇸🇧">🇸🇧 +677</option>
                        <option value="+678" data-flag="🇻🇺">🇻🇺 +678</option>
                        <option value="+679" data-flag="🇫🇯">🇫🇯 +679</option>
                        <option value="+680" data-flag="🇵🇼">🇵🇼 +680</option>
                        <option value="+681" data-flag="🇼🇫">🇼🇫 +681</option>
                        <option value="+682" data-flag="🇨🇰">🇨🇰 +682</option>
                        <option value="+683" data-flag="🇳🇺">🇳🇺 +683</option>
                        <option value="+685" data-flag="🇼🇸">🇼🇸 +685</option>
                        <option value="+686" data-flag="🇰🇮">🇰🇮 +686</option>
                        <option value="+687" data-flag="🇳🇨">🇳🇨 +687</option>
                        <option value="+688" data-flag="🇹🇻">🇹🇻 +688</option>
                        <option value="+689" data-flag="🇵🇫">🇵🇫 +689</option>
                        <option value="+850" data-flag="🇰🇵">🇰🇵 +850</option>
                        <option value="+852" data-flag="🇭🇰">🇭🇰 +852</option>
                        <option value="+853" data-flag="🇲🇴">🇲🇴 +853</option>
                        <option value="+855" data-flag="🇰🇭">🇰🇭 +855</option>
                        <option value="+856" data-flag="🇱🇦">🇱🇦 +856</option>
                        <option value="+880" data-flag="🇧🇩">🇧🇩 +880</option>
                        <option value="+886" data-flag="🇹🇼">🇹🇼 +886</option>
                        <option value="+960" data-flag="🇲🇻">🇲🇻 +960</option>
                        <option value="+961" data-flag="🇱🇧">🇱🇧 +961</option>
                        <option value="+962" data-flag="🇯🇴">🇯🇴 +962</option>
                        <option value="+963" data-flag="🇸🇾">🇸🇾 +963</option>
                        <option value="+964" data-flag="🇮🇶">🇮🇶 +964</option>
                        <option value="+965" data-flag="🇰🇼">🇰🇼 +965</option>
                        <option value="+966" data-flag="🇸🇦">🇸🇦 +966</option>
                        <option value="+967" data-flag="🇾🇪">🇾🇪 +967</option>
                        <option value="+968" data-flag="🇴🇲">🇴🇲 +968</option>
                        <option value="+970" data-flag="🇵🇸">🇵🇸 +970</option>
                        <option value="+971" data-flag="🇦🇪">🇦🇪 +971</option>
                        <option value="+972" data-flag="🇮🇱">🇮🇱 +972</option>
                        <option value="+973" data-flag="🇧🇭">🇧🇭 +973</option>
                        <option value="+974" data-flag="🇶🇦">🇶🇦 +974</option>
                        <option value="+975" data-flag="🇧🇹">🇧🇹 +975</option>
                        <option value="+976" data-flag="🇲🇳">🇲🇳 +976</option>
                        <option value="+977" data-flag="🇳🇵">🇳🇵 +977</option>
                        <option value="+992" data-flag="🇹🇯">🇹🇯 +992</option>
                        <option value="+993" data-flag="🇹🇲">🇹🇲 +993</option>
                        <option value="+994" data-flag="🇦🇿">🇦🇿 +994</option>
                        <option value="+995" data-flag="🇬🇪">🇬🇪 +995</option>
                        <option value="+996" data-flag="🇰🇬">🇰🇬 +996</option>
                        <option value="+998" data-flag="🇺🇿">🇺🇿 +998</option>
                    </select>
                    <input type="tel" name="phone" id="phone_number" value="{{ old('phone') }}" 
                           class="auth-input" style="flex: 1;"
                           placeholder="{{ trans('app.auth.register.phone_number') }}">
                </div>
                <input type="hidden" name="phone_full" id="phone_full">
            </div>
            
            <div style="margin-bottom: 20px;">
                <label class="auth-label">{{ trans('app.auth.register.password') }}</label>
                <input type="password" name="password" required minlength="6"
                       class="auth-input"
                       placeholder="••••••••">
            </div>
            
            <div style="margin-bottom: 25px;">
                <label class="auth-label">{{ trans('app.auth.register.confirm_password') }}</label>
                <input type="password" name="password_confirmation" required minlength="6"
                       class="auth-input"
                       placeholder="••••••••">
            </div>
            
            <button type="submit" class="auth-button">
                <i class="fas fa-user-plus mr-2"></i>{{ trans('app.auth.register.button') }}
            </button>
        </form>
        
        <div class="auth-link-text">
            <p>{{ trans('app.auth.register.has_account') }}</p>
            <a href="{{ route('login') }}" class="auth-link-button">
                <i class="fas fa-sign-in-alt mr-2"></i>{{ trans('app.auth.register.login') }}
            </a>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const countrySelect = document.getElementById('phone_country');
    const phoneInput = document.getElementById('phone_number');
    const phoneFullInput = document.getElementById('phone_full');
    
    // Fonction pour mettre à jour le numéro complet
    function updateFullPhone() {
        const countryCode = countrySelect.value;
        const phoneNumber = phoneInput.value.trim();
        if (phoneNumber) {
            phoneFullInput.value = countryCode + phoneNumber;
        } else {
            phoneFullInput.value = '';
        }
    }
    
    // Écouter les changements
    countrySelect.addEventListener('change', updateFullPhone);
    phoneInput.addEventListener('input', updateFullPhone);
    
    // Si un numéro existe déjà, le parser
    const existingPhone = phoneInput.value;
    if (existingPhone && existingPhone.startsWith('+')) {
        // Essayer de détecter le code pays
        for (let option of countrySelect.options) {
            if (existingPhone.startsWith(option.value)) {
                countrySelect.value = option.value;
                phoneInput.value = existingPhone.substring(option.value.length);
                break;
            }
        }
    }
    
    // Initialiser
    updateFullPhone();
});
</script>
@endsection

