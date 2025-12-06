@php
    $type = $type ?? 'formation';
    $slug = $slug ?? '';
    $name = $name ?? '';
    $style = $style ?? 'default'; // 'default' ou 'inline'
@endphp

<button data-favorite 
        data-favorite-type="{{ $type }}" 
        data-favorite-slug="{{ $slug }}" 
        data-favorite-name="{{ $name }}"
        class="favorite-button favorite-button-{{ $style }}"
        style="
            @if($style === 'inline')
            background: rgba(255, 255, 255, 0.2); 
            border: 2px solid rgba(255, 255, 255, 0.4); 
            color: white; 
            padding: 10px 20px; 
            border-radius: 8px; 
            cursor: pointer; 
            font-weight: 600; 
            display: inline-flex; 
            align-items: center; 
            gap: 8px; 
            transition: all 0.3s ease; 
            backdrop-filter: blur(10px);
            @else
            background: rgba(6, 182, 212, 0.1);
            border: 2px solid rgba(6, 182, 212, 0.3);
            color: #06b6d4;
            padding: 12px 24px;
            border-radius: 10px;
            cursor: pointer;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s ease;
            @endif
        ">
    <i class="far fa-heart"></i>
    <span>Ajouter aux favoris</span>
</button>


