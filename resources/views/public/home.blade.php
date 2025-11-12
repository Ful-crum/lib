@extends('layouts.app')

@section('title', 'Accueil')

@section('content')
<!-- Hero Section -->
<section class="hero-section text-center">
    <div class="container">
        <h1 class="display-4 fw-bold mb-4">Bienvenue à la Bibliothèque</h1>
        <p class="lead mb-5">Découvrez, réservez et empruntez vos livres préférés en quelques clics</p>
        
        <!-- Search Bar -->
        <div class="search-box">
            <form action="{{ url('/books') }}" method="GET" class="d-flex gap-2">
                <input type="text" name="search" class="form-control form-control-lg" 
                       placeholder="Rechercher un livre, auteur, ISBN..." 
                       value="{{ request('search') }}">
                <button type="submit" class="btn btn-light btn-lg">
                    <i class="bi bi-search"></i> Rechercher
                </button>
            </form>
        </div>
    </div>
</section>

<div class="container">
    <!-- Statistiques -->
    <div class="row text-center mb-5">
        <div class="col-md-3 col-sm-6 mb-4">
            <div class="card p-4">
                <div class="card-body">
                    <i class="bi bi-book-fill text-primary" style="font-size: 3rem;"></i>
                    <h3 class="mt-3">{{ $totalBooks ?? '1000+' }}</h3>
                    <p class="text-muted">Livres Disponibles</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 mb-4">
            <div class="card p-4">
                <div class="card-body">
                    <i class="bi bi-people-fill text-success" style="font-size: 3rem;"></i>
                    <h3 class="mt-3">{{ $totalMembers ?? '500+' }}</h3>
                    <p class="text-muted">Membres Actifs</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 mb-4">
            <div class="card p-4">
                <div class="card-body">
                    <i class="bi bi-grid-fill text-warning" style="font-size: 3rem;"></i>
                    <h3 class="mt-3">{{ $totalCategories ?? '20+' }}</h3>
                    <p class="text-muted">Catégories</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 mb-4">
            <div class="card p-4">
                <div class="card-body">
                    <i class="bi bi-arrow-repeat text-info" style="font-size: 3rem;"></i>
                    <h3 class="mt-3">{{ $totalLoans ?? '2000+' }}</h3>
                    <p class="text-muted">Emprunts Réalisés</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Livres Récents -->
    <section class="mb-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold">
                <i class="bi bi-stars text-warning me-2"></i>Derniers Ajouts
            </h2>
            <a href="{{ url('/books') }}" class="btn btn-outline-primary">
                Voir tous <i class="bi bi-arrow-right ms-1"></i>
            </a>
        </div>

        <div class="row">
            @forelse($recentBooks ?? [] as $book)
                <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                    <div class="card h-100">
                        @if($book->cover_image)
                            <img src="{{ asset('storage/' . $book->cover_image) }}" 
                                 class="card-img-top book-card-img" 
                                 alt="{{ $book->title }}">
                        @else
                            <div class="card-img-top book-card-img bg-secondary d-flex align-items-center justify-content-center">
                                <i class="bi bi-book text-white" style="font-size: 5rem;"></i>
                            </div>
                        @endif
                        
                        <div class="card-body">
                            <h5 class="card-title text-truncate">{{ $book->title }}</h5>
                            <p class="card-text text-muted small">
                                <i class="bi bi-person me-1"></i>{{ $book->author }}
                            </p>
                            <p class="card-text">
                                <span class="badge bg-primary">{{ $book->category->name ?? 'Non catégorisé' }}</span>
                                @if($book->available_copies > 0)
                                    <span class="badge bg-success">
                                        <i class="bi bi-check-circle me-1"></i>Disponible ({{ $book->available_copies }})
                                    </span>
                                @else
                                    <span class="badge bg-danger">
                                        <i class="bi bi-x-circle me-1"></i>Indisponible
                                    </span>
                                @endif
                            </p>
                        </div>
                        <div class="card-footer bg-transparent border-top-0">
                            <a href="{{ url('/books/' . $book->id) }}" class="btn btn-outline-primary w-100">
                                <i class="bi bi-eye me-1"></i>Détails
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="alert alert-info text-center">
                        <i class="bi bi-info-circle me-2"></i>
                        Aucun livre disponible pour le moment. Revenez plus tard !
                    </div>
                </div>
            @endforelse
        </div>
    </section>

    <!-- Catégories Populaires -->
    <section class="mb-5">
        <h2 class="fw-bold mb-4">
            <i class="bi bi-grid-fill text-primary me-2"></i>Catégories Populaires
        </h2>

        <div class="row">
            @forelse($popularCategories ?? [] as $category)
                <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                    <a href="{{ url('/books?category=' . $category->id) }}" 
                       class="text-decoration-none">
                        <div class="card text-center h-100 border-primary">
                            <div class="card-body">
                                <i class="bi bi-bookmark-fill text-primary" style="font-size: 3rem;"></i>
                                <h5 class="card-title mt-3">{{ $category->name }}</h5>
                                <p class="card-text text-muted small">
                                    {{ $category->books_count ?? 0 }} livre(s)
                                </p>
                            </div>
                        </div>
                    </a>
                </div>
            @empty
                <div class="col-12">
                    <div class="alert alert-info text-center">
                        <i class="bi bi-info-circle me-2"></i>
                        Aucune catégorie disponible.
                    </div>
                </div>
            @endforelse
        </div>
    </section>

    <!-- Call to Action -->
    @guest
        <section class="text-center py-5 bg-light rounded">
            <div class="container">
                <h2 class="fw-bold mb-3">Rejoignez Notre Bibliothèque !</h2>
                <p class="lead mb-4">Inscrivez-vous gratuitement et commencez à emprunter des livres dès aujourd'hui</p>
                <div class="d-flex gap-3 justify-content-center">
                    <a href="{{ route('register') }}" class="btn btn-primary btn-lg">
                        <i class="bi bi-person-plus me-2"></i>S'inscrire
                    </a>
                    <a href="{{ route('login') }}" class="btn btn-outline-primary btn-lg">
                        <i class="bi bi-box-arrow-in-right me-2"></i>Se connecter
                    </a>
                </div>
            </div>
        </section>
    @endguest
</div>
@endsection