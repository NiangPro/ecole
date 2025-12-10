@extends('dashboard.layout')

@section('dashboard-content')
<div class="paid-courses-container">
    @if($purchases->count() > 0)
    <div class="courses-grid">
        @foreach($purchases as $purchase)
        @php $course = $purchase->course; @endphp
        <div class="course-card">
            <a href="{{ route('dashboard.paid-courses.show', $course->id) }}" class="course-card-link">
                <div class="course-image-wrapper">
                    @if($course->cover_image)
                        @if(($course->cover_type ?? 'internal') === 'internal')
                            <img src="{{ asset('storage/' . $course->cover_image) }}" alt="{{ $course->title }}" class="course-image">
                        @else
                            <img src="{{ $course->cover_image }}" alt="{{ $course->title }}" class="course-image" onerror="this.src='{{ asset('images/default-course.jpg') }}'">
                        @endif
                    @else
                        <div class="course-image-placeholder">
                            <i class="fas fa-graduation-cap"></i>
                        </div>
                    @endif
                    <div class="course-overlay">
                        <span class="course-status-badge">
                            <i class="fas fa-check-circle"></i>
                            Acheté
                        </span>
                    </div>
                </div>
                <div class="course-content">
                    <h3 class="course-title">{{ $course->title }}</h3>
                    @if($course->description)
                    <p class="course-description">{{ \Illuminate\Support\Str::limit($course->description, 100) }}</p>
                    @endif
                    <div class="course-meta">
                        @if($course->duration_hours)
                        <span class="course-meta-item">
                            <i class="fas fa-clock"></i>
                            {{ $course->duration_hours }}h
                        </span>
                        @endif
                        @if($course->chapters && $course->chapters->count() > 0)
                        <span class="course-meta-item">
                            <i class="fas fa-list-ul"></i>
                            {{ $course->chapters->count() }} chapitres
                        </span>
                        @endif
                    </div>
                    <div class="course-footer">
                        <span class="course-purchased-date">
                            @if($purchase->purchased_at)
                                Acheté le {{ $purchase->purchased_at->format('d/m/Y') }}
                            @else
                                <i class="fas fa-crown"></i> Accès Premium
                            @endif
                        </span>
                        <span class="course-action">
                            Continuer <i class="fas fa-arrow-right"></i>
                        </span>
                    </div>
                </div>
            </a>
        </div>
        @endforeach
    </div>

    <!-- Pagination -->
    <div class="pagination-wrapper">
        {{ $purchases->links() }}
    </div>
    @else
    <div class="empty-state">
        <div class="empty-state-icon">
            <i class="fas fa-graduation-cap"></i>
        </div>
        <h3>Aucun cours payant</h3>
        <p>Vous n'avez pas encore acheté de cours payant.</p>
        <a href="{{ route('monetization.courses') }}" class="btn-primary">
            <i class="fas fa-shopping-cart"></i>
            Découvrir les cours
        </a>
    </div>
    @endif
</div>

<style>
.paid-courses-container {
    padding: 20px 0;
}

.courses-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 25px;
    margin-bottom: 30px;
}

.course-card {
    background: white;
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    transition: all 0.3s ease;
}

body.dark-mode .course-card {
    background: rgba(30, 41, 59, 0.8);
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
}

.course-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(6, 182, 212, 0.3);
}

.course-card-link {
    text-decoration: none;
    color: inherit;
    display: block;
}

.course-image-wrapper {
    position: relative;
    width: 100%;
    height: 200px;
    overflow: hidden;
    background: linear-gradient(135deg, #06b6d4, #14b8a6);
}

.course-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.course-image-placeholder {
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, #06b6d4, #14b8a6);
    color: white;
    font-size: 3rem;
}

.course-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(to bottom, transparent, rgba(0, 0, 0, 0.6));
    display: flex;
    align-items: flex-end;
    padding: 15px;
}

.course-status-badge {
    background: rgba(16, 185, 129, 0.9);
    color: white;
    padding: 6px 12px;
    border-radius: 6px;
    font-size: 0.85rem;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 6px;
}

.course-content {
    padding: 20px;
}

.course-title {
    font-size: 1.2rem;
    font-weight: 700;
    color: #1e293b;
    margin-bottom: 10px;
    line-height: 1.4;
}

body.dark-mode .course-title {
    color: white;
}

.course-description {
    color: #64748b;
    font-size: 0.9rem;
    line-height: 1.6;
    margin-bottom: 15px;
}

body.dark-mode .course-description {
    color: rgba(255, 255, 255, 0.7);
}

.course-meta {
    display: flex;
    gap: 15px;
    margin-bottom: 15px;
    flex-wrap: wrap;
}

.course-meta-item {
    display: flex;
    align-items: center;
    gap: 6px;
    color: #64748b;
    font-size: 0.85rem;
    font-weight: 600;
}

body.dark-mode .course-meta-item {
    color: rgba(255, 255, 255, 0.6);
}

.course-meta-item i {
    color: #06b6d4;
}

.course-footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding-top: 15px;
    border-top: 1px solid #e2e8f0;
}

body.dark-mode .course-footer {
    border-top-color: rgba(6, 182, 212, 0.2);
}

.course-purchased-date {
    color: #64748b;
    font-size: 0.85rem;
}

body.dark-mode .course-purchased-date {
    color: rgba(255, 255, 255, 0.6);
}

.course-action {
    color: #06b6d4;
    font-weight: 600;
    font-size: 0.9rem;
    display: flex;
    align-items: center;
    gap: 6px;
    transition: all 0.3s ease;
}

.course-card:hover .course-action {
    gap: 10px;
}

.empty-state {
    text-align: center;
    padding: 80px 20px;
    background: white;
    border-radius: 16px;
    border: 2px solid rgba(6, 182, 212, 0.2);
}

body.dark-mode .empty-state {
    background: rgba(30, 41, 59, 0.8);
    border-color: rgba(6, 182, 212, 0.3);
}

.empty-state-icon {
    width: 100px;
    height: 100px;
    margin: 0 auto 25px;
    border-radius: 50%;
    background: linear-gradient(135deg, rgba(6, 182, 212, 0.2), rgba(20, 184, 166, 0.2));
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 3rem;
    color: #06b6d4;
}

.empty-state h3 {
    font-size: 1.5rem;
    font-weight: 700;
    color: #1e293b;
    margin-bottom: 10px;
}

body.dark-mode .empty-state h3 {
    color: white;
}

.empty-state p {
    color: #64748b;
    font-size: 1rem;
    margin-bottom: 25px;
}

body.dark-mode .empty-state p {
    color: rgba(255, 255, 255, 0.7);
}

.btn-primary {
    display: inline-flex;
    align-items: center;
    gap: 10px;
    padding: 12px 24px;
    background: linear-gradient(135deg, #06b6d4, #14b8a6);
    color: white;
    border-radius: 10px;
    text-decoration: none;
    font-weight: 600;
    transition: all 0.3s ease;
    box-shadow: 0 4px 15px rgba(6, 182, 212, 0.3);
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(6, 182, 212, 0.4);
}

.pagination-wrapper {
    display: flex;
    justify-content: center;
    margin-top: 30px;
}

@media (max-width: 768px) {
    .courses-grid {
        grid-template-columns: 1fr;
    }
}
</style>
@endsection

