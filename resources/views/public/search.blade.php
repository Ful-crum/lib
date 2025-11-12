@extends('layouts.app')

@section('title', 'Recherche de Livres')

@section('content')
<div class="container py-5">
    <div class="row">
        <!-- Filtres Sidebar -->
        <div class="col-lg-3 mb-4">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <i class="bi bi-funnel me-2"></i>Filtres
                    </h5>
                </div>
                <div class="card-body">
                    <form action="{{ url('/books') }}" method="GET">
                        <!-- Recherche par mot-clé -->
                        <div class="mb-3">
                            <label for="search" class="form-label fw-bold">Rechercher</label>
                            <input type="text" 
                                   class="form-control" 
                                   id="search" 
                                   name="search" 
                                   placeholder="Titre, auteur, ISBN..."
                                   value="{{ request('search') }}">
                        </div>

                        <!-- Catégorie -->
                        <div class="mb-3">
                            <label for="category" class="form-label fw-bold">Catégorie</label>
                            <select class="form-select" id="category" name="category">
                                <option value="">Toutes les catégories</option>
                                @foreach($categories ?? [] as $category)
                                    <option value="{{ $category->id }}" 
                                            {{ request('category') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Disponibilité -->
                        <div class="mb-3">
                            <label class="form-label fw-bold">Disponibilité</label>
                            <div class="form-check">
                                <input class="form-check-input" 
                                       type="checkbox" 
                                       id="available" 
                                       name="available" 
                                       value="1"
                                       {{ request('available') ? 'checked' : '' }}>
                                <label class="form-check-label" for="available">
                                    Uniquement les livres disponibles
                                </label>
                            </div>
                        </div>

                        <!-- Année de publication -->
                        <div class="mb-3">
                            <label for="year" class="form-label fw-bold">Année</label>
                            <input type="number" 
                                   class="form-control" 
                                   id="year" 
                                   name="year" 
                                   placeholder="Ex: 2024"
                                   min="1900"
                                   max="{{ date('Y') }}"
                                   value="{{ request('year') }}">
                        </div>

                        <!-- Tri -->
                        <div class="mb-3">
                            <label for="sort" class="form-label fw-bold">Trier par</label>
                            <select class="form-select" id="sort" name="sort">
                                <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>
                                    Plus récent
                                </option>
                                <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>
                                    Plus ancien
                                </option>
                                <option value="title_asc" {{ request('sort') == 'title_asc' ? 'selected' : '' }}>
                                    Titre (A-Z)
                                </option>
                                <option value="title_desc" {{ request('sort') == 'title_desc' ? 'selected' : '' }}>
                                    Titre (Z-A)
                                </option>
                                <option value="author_asc" {{ request('sort') == 'author_asc' ? 'selected' : '' }}>
                                    Auteur (A-Z)
                                </option>
                            </select>
                        </div>

                        <!-- Boutons -->
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-search me-1"></i>Rechercher
                            </button>
                            <a href="{{ url('/books') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-x-circle me-1"></i>Réinitialiser
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Résultats -->
        <div class="col-lg-9">
            <!-- En-tête des résultats -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="fw-bold">
                    <i class="bi bi-book me-2"></i>
                    @if(request('search'))
                        Résultats pour "{{ request('search') }}"
                    @else
                        Tous les livres
                    @endif
                </h2>
                <span class="badge bg-secondary">
                    {{ $books->total() ?? 0 }} livre(s) trouvé(s)
                </span>
            </div>

            <!-- Affichage des résultats -->
            @if(isset($books) && $books->count() > 0)
                <div class="row">
                    @foreach($books as $book)
                        <div class="col-md-6 col-lg-4 mb-4">
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
                                
                                <div class="card-body d-flex flex-column">
                                    <h5 class="card-title">{{ $book->title }}</h5>
                                    <p class="card-text text-muted small mb-2">
                                        <i class="bi bi-person me-1"></i>{{ $book->author }}
                                    </p>
                                    
                                    <div class="mb-2">
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
                                    </div>

                                    @if($book->publication_year)
                                        <p class="card-text small text-muted">
                                            <i class="bi bi-calendar me-1"></i>{{ $book->publication_year }}
                                        </p>
                                    @endif

                                    <div class="mt-auto pt-2">
                                        <a href="{{ url('/books/' . $book->id) }}" class="btn btn-outline-primary w-100">
                                            <i class="bi bi-eye me-1"></i>Voir les détails
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-center mt-4">
                    {{ $books->links() }}
                </div>
            @else
                <!-- Aucun résultat -->
                <div class="alert alert-info text-center py-5">
                    <i class="bi bi-search" style="font-size: 4rem;"></i>
                    <h4 class="mt-3">Aucun livre trouvé</h4>
                    <p class="mb-3">Essayez de modifier vos critères de recherche</p>
                    <a href="{{ url('/books') }}" class="btn btn-primary">
                        <i class="bi bi-arrow-clockwise me-1"></i>Voir tous les livres
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection