@extends('layouts.app')

@section('title', $book->title ?? 'Détails du Livre')

@section('content')
<div class="container py-5">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ url('/') }}">Accueil</a></li>
            <li class="breadcrumb-item"><a href="{{ url('/books') }}">Livres</a></li>
            <li class="breadcrumb-item active">{{ $book->title ?? 'Détails' }}</li>
        </ol>
    </nav>

    <div class="row">
        <!-- Image du livre -->
        <div class="col-lg-4 mb-4">
            <div class="card shadow">
                @if($book->cover_image ?? false)
                    <img src="{{ asset('storage/' . $book->cover_image) }}" 
                         class="card-img-top" 
                         alt="{{ $book->title }}"
                         style="height: 500px; object-fit: cover;">
                @else
                    <div class="card-img-top bg-secondary d-flex align-items-center justify-content-center" 
                         style="height: 500px;">
                        <i class="bi bi-book text-white" style="font-size: 8rem;"></i>
                    </div>
                @endif
                
                <div class="card-body">
                    <!-- Statut de disponibilité -->
                    @if(($book->available_copies ?? 0) > 0)
                        <div class="alert alert-success mb-3">
                            <i class="bi bi-check-circle-fill me-2"></i>
                            <strong>Disponible</strong> ({{ $book->available_copies }} exemplaire(s))
                        </div>

                        @auth
                            @if(auth()->user()->role === 'member' || auth()->user()->role === 'admin')
                                @if(!auth()->user()->is_blocked)
                                    <form action="{{ url('/member/reservations') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="book_id" value="{{ $book->id }}">
                                        <button type="submit" class="btn btn-primary w-100 btn-lg mb-2">
                                            <i class="bi bi-bookmark-plus me-2"></i>Réserver ce livre
                                        </button>
                                    </form>
                                @else
                                    <div class="alert alert-danger">
                                        <i class="bi bi-exclamation-triangle me-2"></i>
                                        Votre compte est bloqué
                                    </div>
                                @endif
                            @endif
                        @else
                            <a href="{{ route('login') }}" class="btn btn-primary w-100 btn-lg mb-2">
                                <i class="bi bi-box-arrow-in-right me-2"></i>Connectez-vous pour réserver
                            </a>
                        @endauth
                    @else
                        <div class="alert alert-danger">
                            <i class="bi bi-x-circle-fill me-2"></i>
                            <strong>Indisponible</strong>
                        </div>
                    @endif

                    <!-- Bouton Retour -->
                    <a href="{{ url('/books') }}" class="btn btn-outline-secondary w-100">
                        <i class="bi bi-arrow-left me-2"></i>Retour à la recherche
                    </a>
                </div>
            </div>
        </div>

        <!-- Informations du livre -->
        <div class="col-lg-8">
            <div class="card shadow">
                <div class="card-body">
                    <!-- Titre et catégorie -->
                    <div class="mb-4">
                        <h1 class="display-5 fw-bold mb-2">{{ $book->title ?? 'Titre non disponible' }}</h1>
                        <span class="badge bg-primary fs-6">
                            {{ $book->category->name ?? 'Non catégorisé' }}
                        </span>
                    </div>

                    <!-- Informations principales -->
                    <div class="row mb-4">
                        <div class="col-md-6 mb-3">
                            <h6 class="text-muted">
                                <i class="bi bi-person-fill me-2"></i>Auteur
                            </h6>
                            <p class="fs-5">{{ $book->author ?? 'Inconnu' }}</p>
                        </div>

                        <div class="col-md-6 mb-3">
                            <h6 class="text-muted">
                                <i class="bi bi-upc me-2"></i>ISBN
                            </h6>
                            <p class="fs-5 font-monospace">{{ $book->isbn ?? 'N/A' }}</p>
                        </div>

                        @if($book->publisher ?? false)
                            <div class="col-md-6 mb-3">
                                <h6 class="text-muted">
                                    <i class="bi bi-building me-2"></i>Éditeur
                                </h6>
                                <p class="fs-5">{{ $book->publisher }}</p>
                            </div>
                        @endif

                        @if($book->publication_year ?? false)
                            <div class="col-md-6 mb-3">
                                <h6 class="text-muted">
                                    <i class="bi bi-calendar-event me-2"></i>Année de publication
                                </h6>
                                <p class="fs-5">{{ $book->publication_year }}</p>
                            </div>
                        @endif

                        <div class="col-md-6 mb-3">
                            <h6 class="text-muted">
                                <i class="bi bi-stack me-2"></i>Exemplaires
                            </h6>
                            <p class="fs-5">
                                <span class="text-success">{{ $book->available_copies ?? 0 }}</span> / 
                                {{ $book->total_copies ?? 0 }}
                            </p>
                        </div>
                    </div>

                    <!-- Description -->
                    @if($book->description ?? false)
                        <div class="mb-4">
                            <h5 class="fw-bold mb-3">
                                <i class="bi bi-card-text me-2"></i>Description
                            </h5>
                            <p class="text-justify" style="text-align: justify;">
                                {{ $book->description }}
                            </p>
                        </div>
                    @endif

                    <!-- Informations supplémentaires -->
                    <div class="border-top pt-4">
                        <h5 class="fw-bold mb-3">
                            <i class="bi bi-info-circle me-2"></i>Informations supplémentaires
                        </h5>
                        <ul class="list-unstyled">
                            <li class="mb-2">
                                <i class="bi bi-clock-history me-2 text-primary"></i>
                                Durée d'emprunt : <strong>14 jours</strong>
                            </li>
                            <li class="mb-2">
                                <i class="bi bi-arrow-repeat me-2 text-primary"></i>
                                Possibilité de prolongation : <strong>1 fois (7 jours)</strong>
                            </li>
                            <li class="mb-2">
                                <i class="bi bi-exclamation-triangle me-2 text-warning"></i>
                                Amende en cas de retard : <strong>0.50€/jour</strong>
                            </li>
                        </ul>
                    </div>

                    <!-- Date d'ajout -->
                    <div class="border-top pt-3 mt-3">
                        <small class="text-muted">
                            <i class="bi bi-calendar-plus me-1"></i>
                            Ajouté le {{ $book->created_at ? $book->created_at->format('d/m/Y') : 'N/A' }}
                        </small>
                    </div>
                </div>
            </div>

            <!-- Livres similaires (optionnel) -->
            @if(isset($similarBooks) && $similarBooks->count() > 0)
                <div class="card shadow mt-4">
                    <div class="card-header bg-light">
                        <h5 class="mb-0">
                            <i class="bi bi-bookmark-star me-2"></i>Livres similaires
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            @foreach($similarBooks->take(3) as $similar)
                                <div class="col-md-4 mb-3">
                                    <div class="card h-100">
                                        @if($similar->cover_image)
                                            <img src="{{ asset('storage/' . $similar->cover_image) }}" 
                                                 class="card-img-top" 
                                                 style="height: 200px; object-fit: cover;"
                                                 alt="{{ $similar->title }}">
                                        @else
                                            <div class="card-img-top bg-secondary d-flex align-items-center justify-content-center" 
                                                 style="height: 200px;">
                                                <i class="bi bi-book text-white" style="font-size: 3rem;"></i>
                                            </div>
                                        @endif
                                        <div class="card-body">
                                            <h6 class="card-title text-truncate">{{ $similar->title }}</h6>
                                            <p class="card-text small text-muted">{{ $similar->author }}</p>
                                            <a href="{{ url('/books/' . $similar->id) }}" class="btn btn-sm btn-outline-primary w-100">
                                                Voir
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection