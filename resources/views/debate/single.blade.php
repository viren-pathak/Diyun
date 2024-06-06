@extends('debate.master')

@section('content')

<section class="debate-single-content">
    <div class="container">

        <div class="content__sunburst-mini-map">
        </div>

        <div class="content__body-container">
            <div class="content__body">
                <div class="ancestor-stack">
                    <div class="ancestor-cards">
                        @foreach($ancestors as $ancestor)
                            <div class="ancestor-claim claims {{ $ancestor->side ? $ancestor->side . '-child-claim' : 'root-debate-claim' }}">
                                <div class="claim-card" data-debate-id="{{ $ancestor->id }}" data-debate-slug="{{ $ancestor->slug }}">
                                    <div class="claim-header">
                                        <div class="votes-btn-container">
                                            {{-- PRGRESS BAR BLADE in views>components>progress-bar.blade.php --}}
                                            <x-progress-bar :value="$averageVotes['ancestors'][$ancestor->id]" />
                                            <button class="votes-btn" data-target="votesContainerAncestor{{ $ancestor->id }}">Votes</button>
                                        </div>
                                        <div class="comments-btn-conaitner">
                                            <button class="comment-btn">
                                                <span class="svg-icon svg-icon-primary svg-icon-2x">
                                                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                                        <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                            <rect x="0" y="0" width="24" height="24"/>
                                                            <path d="M16,15.6315789 L16,12 C16,10.3431458 14.6568542,9 13,9 L6.16183229,9 L6.16183229,5.52631579 C6.16183229,4.13107011 7.29290239,3 8.68814808,3 L20.4776218,3 C21.8728674,3 23.0039375,4.13107011 23.0039375,5.52631579 L23.0039375,13.1052632 L23.0206157,17.786793 C23.0215995,18.0629336 22.7985408,18.2875874 22.5224001,18.2885711 C22.3891754,18.2890457 22.2612702,18.2363324 22.1670655,18.1421277 L19.6565168,15.6315789 L16,15.6315789 Z" fill="#000000"/>
                                                            <path d="M1.98505595,18 L1.98505595,13 C1.98505595,11.8954305 2.88048645,11 3.98505595,11 L11.9850559,11 C13.0896254,11 13.9850559,11.8954305 13.9850559,13 L13.9850559,18 C13.9850559,19.1045695 13.0896254,20 11.9850559,20 L4.10078614,20 L2.85693427,21.1905292 C2.65744295,21.3814685 2.34093638,21.3745358 2.14999706,21.1750444 C2.06092565,21.0819836 2.01120804,20.958136 2.01120804,20.8293182 L2.01120804,18.32426 C1.99400175,18.2187196 1.98505595,18.1104045 1.98505595,18 Z M6.5,14 C6.22385763,14 6,14.2238576 6,14.5 C6,14.7761424 6.22385763,15 6.5,15 L11.5,15 C11.7761424,15 12,14.7761424 12,14.5 C12,14.2238576 11.7761424,14 11.5,14 L6.5,14 Z M9.5,16 C9.22385763,16 9,16.2238576 9,16.5 C9,16.7761424 9.22385763,17 9.5,17 L11.5,17 C11.7761424,17 12,16.7761424 12,16.5 C12,16.2238576 11.7761424,16 11.5,16 L9.5,16 Z" fill="#000000" opacity="0.3"/>
                                                        </g>
                                                    </svg>
                                                </span>
                                                <span class="comment-count">{{ $ancestor->comments->count() > 0 ? $ancestor->comments->count() : '' }}</span>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="claim-text">
                                        <p class="claim-text__content">{{ $ancestor->title }}</p>
                                    </div>
                                </div>
                                
                                <div class="comment-form-container" style="display:none;">
                                    <div class="comment-container__header">
                                        <button type="button" class="close-form-btn close-comment-container">&#10005;</button>
                                    </div>
                                    <div class="comment-container__content">
                                        <ul class="comments-list">
                                            @foreach($ancestor->comments as $comment)
                                                <li class="comment-box">
                                                    <div class="comment-identity-avatar">
                                                        <img src="{{ $comment->user->profile_picture }}" alt="Profile Picture" class="comment-profile-picture">
                                                    </div>
                                                    <div class="comment-box-body">
                                                        <div class="comment-box-content">
                                                            <p class="comment-content__text">{{ $comment->comment }}</p>
                                                        </div>
                                                        <div class="comment-meta-details">
                                                            <span class="comment-username">{{ $comment->user->username }}</span>
                                                            <span class="comment-time">{{ $comment->created_at->diffForHumans() }}</span>
                                                        </div>
                                                    </div>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                    <div class="new-comment-editor">
                                        @if (auth()->check())
                                        <form action="{{ route('debate.comment', $ancestor->id) }}" method="POST" class="comment-form">
                                            @csrf
                                            <input type="text" class="new-comment-editor__input-field" name="comment" placeholder="Enter your comment" required>
                                            <button type="submit" class="new-comment-editor__submit-button">&#8594;</button>
                                        </form>
                                        @else
                                            <button class="comment-login-btn" onclick="openLoginForm()">Please log in to write comments</button>
                                        @endif
                                    </div>
                                </div>
                                <div id="votesContainerAncestor{{ $ancestor->id }}" class="votes-drafts-container" style="display:none;">
                                    @if($ancestor->userVoted())
                                        <form action="{{ route('debate.deleteVote', $ancestor->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger">&#8634;</button>
                                        </form>
                                    @endif

                                    <canvas id="votesChartAncestor{{ $ancestor->id }}" width="200" height="40"></canvas>

                                    @if ($ancestor->voting_allowed)
                                        <form action="{{ route('debate.vote', $ancestor->id) }}" method="POST">
                                            @csrf
                                            <div class="vote-buttons">
                                                @for ($i = 0; $i <= 4; $i++)
                                                    <button type="submit" name="rating" value="{{ $i }}">{{ $i }}</button>
                                                @endfor
                                            </div>
                                        </form>
                                    @else
                                        <p>Enable voting for this debate to allow voting.</p>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>

                </div>

                <main class="content__selected-and-columns">
                    <div class="selected-claim-container">
                        <div class="selected-claim claims {{ $debate->side ? $debate->side . '-child-claim' : 'root-debate-claim' }}">
                            <div class="claim-card" data-debate-id="{{ $debate->id }}" data-debate-slug="{{ $debate->slug }}">
                                <div class="claim-header">
                                    <div class="votes-btn-container">
                                        {{-- PRGRESS BAR BLADE in views>components>progress-bar.blade.php --}}
                                        <x-progress-bar :value="$averageVotes['debate']" />
                                        <button class="votes-btn" data-target="votesContainer{{ $debate->id }}">Votes</button>
                                    </div>
                                    <div class="comments-btn-conaitner">
                                        <button class="comment-btn">
                                            <span class="svg-icon svg-icon-primary svg-icon-2x">
                                                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                        <rect x="0" y="0" width="24" height="24"/>
                                                        <path d="M16,15.6315789 L16,12 C16,10.3431458 14.6568542,9 13,9 L6.16183229,9 L6.16183229,5.52631579 C6.16183229,4.13107011 7.29290239,3 8.68814808,3 L20.4776218,3 C21.8728674,3 23.0039375,4.13107011 23.0039375,5.52631579 L23.0039375,13.1052632 L23.0206157,17.786793 C23.0215995,18.0629336 22.7985408,18.2875874 22.5224001,18.2885711 C22.3891754,18.2890457 22.2612702,18.2363324 22.1670655,18.1421277 L19.6565168,15.6315789 L16,15.6315789 Z" fill="#000000"/>
                                                        <path d="M1.98505595,18 L1.98505595,13 C1.98505595,11.8954305 2.88048645,11 3.98505595,11 L11.9850559,11 C13.0896254,11 13.9850559,11.8954305 13.9850559,13 L13.9850559,18 C13.9850559,19.1045695 13.0896254,20 11.9850559,20 L4.10078614,20 L2.85693427,21.1905292 C2.65744295,21.3814685 2.34093638,21.3745358 2.14999706,21.1750444 C2.06092565,21.0819836 2.01120804,20.958136 2.01120804,20.8293182 L2.01120804,18.32426 C1.99400175,18.2187196 1.98505595,18.1104045 1.98505595,18 Z M6.5,14 C6.22385763,14 6,14.2238576 6,14.5 C6,14.7761424 6.22385763,15 6.5,15 L11.5,15 C11.7761424,15 12,14.7761424 12,14.5 C12,14.2238576 11.7761424,14 11.5,14 L6.5,14 Z M9.5,16 C9.22385763,16 9,16.2238576 9,16.5 C9,16.7761424 9.22385763,17 9.5,17 L11.5,17 C11.7761424,17 12,16.7761424 12,16.5 C12,16.2238576 11.7761424,16 11.5,16 L9.5,16 Z" fill="#000000" opacity="0.3"/>
                                                    </g>
                                                </svg>
                                            </span>
                                            <span class="comment-count">{{ $debate->comments->count() > 0 ? $debate->comments->count() : '' }}</span>
                                        </button>
                                    </div>
                                </div>
                                <div class="claim-text">
                                    <p class="claim-text__content">{{ $debate->title }}</p>
                                </div>
                            </div>
                            <div class="comment-form-container" style="display:none;">
                                <div class="comment-container__header">
                                    <button type="button" class="close-form-btn close-comment-container">&#10005;</button>
                                </div>
                                <div class="comment-container__content">
                                    <ul class="comments-list">
                                        @foreach($debate->comments as $comment)
                                            <li class="comment-box">
                                                <div class="comment-identity-avatar">
                                                    <img src="{{ $comment->user->profile_picture }}" alt="Profile Picture" class="comment-profile-picture">
                                                </div>
                                                <div class="comment-box-body">
                                                    <div class="comment-box-content">
                                                        <p class="comment-content__text">{{ $comment->comment }}</p>
                                                    </div>
                                                    <div class="comment-meta-details">
                                                        <span class="comment-username">{{ $comment->user->username }}</span>
                                                        <span class="comment-time">{{ $comment->created_at->diffForHumans() }}</span>
                                                    </div>
                                                </div>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                                <div class="new-comment-editor">
                                    @if (auth()->check())
                                    <form action="{{ route('debate.comment', $debate->id) }}" method="POST" class="comment-form">
                                        @csrf
                                        <input type="text" class="new-comment-editor__input-field" name="comment" placeholder="Enter your comment" required>
                                        <button type="submit" class="new-comment-editor__submit-button">&#8594;</button>
                                    </form>
                                    @else
                                        <button class="comment-login-btn" onclick="openLoginForm()">Please log in to write comments</button>
                                    @endif
                                </div>
                            </div>
                            <div id="votesContainer{{ $debate->id }}" class="votes-drafts-container" style="display:none;">
                                @if($debate->userVoted())
                                    <form action="{{ route('debate.deleteVote', $debate->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger">&#8634;</button>
                                    </form>
                                @endif

                                <canvas id="votesChart" width="200" height="40"></canvas>

                                @if ($debate->voting_allowed)
                                    <form action="{{ route('debate.vote', $debate->id) }}" method="POST">
                                        @csrf
                                        <div class="vote-buttons">
                                            @for ($i = 0; $i <= 4; $i++)
                                                <button type="submit" name="rating" value="{{ $i }}">{{ $i }}</button>
                                            @endfor
                                        </div>
                                    </form>
                                @else
                                    <p>Enable voting for this debate to allow voting.</p>
                                @endif
                            </div>

                        </div>
                    </div>
                    
                    <div class="columns-container">
                        <div class="columns-container__column-headers">
                            <div class="column-box column-box--header--pro">
                                <div class="column-box--header--pro--contents">
                                    <p class="column-box--pro--info">Pros</p>
                                    <button type="button" class="btn-danger btn add-pro-btn" data-authenticated="{{ auth()->check() }}">+</button>
                                </div>
                                <div class="add-pro-form-container add-child-form-container" style="display:none;">
                                    <form action="{{ route('debate.addPro', $debate->id) }}" method="POST" class="add-pro-form">
                                        @csrf
                                        <input class="child-form-input" type="text" name="title" placeholder="Enter pro argument" maxlength="500">
                                        <p class="char-count">500 characters remaining</p>
                                        <button class="child-form-submit" type="submit">Submit</button>
                                        <button type="button" class="close-form-btn">Close</button>
                                    </form>
                                </div>
                            </div>
                            <div class="column-box column-box--header--con">
                                <div class="column-box--header--con--contents">
                                    <p class="column-box--con--info">Cons</p>
                                    <button type="button" class="btn-danger btn add-cons-btn" data-authenticated="{{ auth()->check() }}">+</button>
                                </div>
                                <div class="add-con-form-container add-child-form-container" style="display:none;">
                                    <form action="{{ route('debate.addCon', $debate->id) }}" method="POST" class="add-con-form">
                                        @csrf
                                        <input class="child-form-input" type="text" name="title" placeholder="Enter con argument" maxlength="500">
                                        <p class="char-count">500 characters remaining</p>
                                        <button class="child-form-submit" type="submit">Submit</button>
                                        <button type="button" class="close-form-btn">Close</button>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <div class="columns-container__column-contents">
                            <div class="column-box column-box--claims column-box--claims-pro">
                                <ul class="column__content--pros">
                                    @foreach($pros as $pro)
                                        <li class="pro-argument">
                                            <div class="actionable-claim-container">
                                                <div class="actionable-claim claims {{ $pro->side }}-child-claim">
                                                    <div class="claim-card child-claim-card" data-debate-id="{{ $pro->id }}" data-debate-slug="{{ $pro->slug }}">
                                                        <div class="claim-header">
                                                            <div class="votes-btn-container">
                                                                {{-- PRGRESS BAR BLADE in views>components>progress-bar.blade.php --}}
                                                                <x-progress-bar :value="$averageVotes['pros'][$pro->id]" />
                                                                <button class="votes-btn" data-target="votesContainerPro{{ $pro->id }}">Votes</button>
                                                            </div>
                                                            <div class="comments-btn-conaitner">
                                                                <button class="comment-btn">
                                                                    <span class="svg-icon svg-icon-primary svg-icon-2x">
                                                                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                                                        <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                                            <rect x="0" y="0" width="24" height="24"/>
                                                                            <path d="M16,15.6315789 L16,12 C16,10.3431458 14.6568542,9 13,9 L6.16183229,9 L6.16183229,5.52631579 C6.16183229,4.13107011 7.29290239,3 8.68814808,3 L20.4776218,3 C21.8728674,3 23.0039375,4.13107011 23.0039375,5.52631579 L23.0039375,13.1052632 L23.0206157,17.786793 C23.0215995,18.0629336 22.7985408,18.2875874 22.5224001,18.2885711 C22.3891754,18.2890457 22.2612702,18.2363324 22.1670655,18.1421277 L19.6565168,15.6315789 L16,15.6315789 Z" fill="#000000"/>
                                                                            <path d="M1.98505595,18 L1.98505595,13 C1.98505595,11.8954305 2.88048645,11 3.98505595,11 L11.9850559,11 C13.0896254,11 13.9850559,11.8954305 13.9850559,13 L13.9850559,18 C13.9850559,19.1045695 13.0896254,20 11.9850559,20 L4.10078614,20 L2.85693427,21.1905292 C2.65744295,21.3814685 2.34093638,21.3745358 2.14999706,21.1750444 C2.06092565,21.0819836 2.01120804,20.958136 2.01120804,20.8293182 L2.01120804,18.32426 C1.99400175,18.2187196 1.98505595,18.1104045 1.98505595,18 Z M6.5,14 C6.22385763,14 6,14.2238576 6,14.5 C6,14.7761424 6.22385763,15 6.5,15 L11.5,15 C11.7761424,15 12,14.7761424 12,14.5 C12,14.2238576 11.7761424,14 11.5,14 L6.5,14 Z M9.5,16 C9.22385763,16 9,16.2238576 9,16.5 C9,16.7761424 9.22385763,17 9.5,17 L11.5,17 C11.7761424,17 12,16.7761424 12,16.5 C12,16.2238576 11.7761424,16 11.5,16 L9.5,16 Z" fill="#000000" opacity="0.3"/>
                                                                        </g>
                                                                        </svg>
                                                                    </span>
                                                                    <span class="comment-count">{{ $pro->comments->count() > 0 ? $pro->comments->count() : '' }}</span>
                                                                </button>
                                                            </div>
                                                        </div>
                                                        <div class="claim-text">
                                                            <p class="claim-text__content">{{ $pro->title }}</p>
                                                        </div>
                                                    </div>
                                                    <div class="comment-form-container" style="display:none;">
                                                        <div class="comment-container__header">
                                                            <button type="button" class="close-form-btn close-comment-container">&#10005;</button>
                                                        </div>
                                                        <div class="comment-container__content">
                                                            <ul class="comments-list">
                                                                @foreach($pro->comments as $comment)
                                                                    <li class="comment-box">
                                                                        <div class="comment-identity-avatar">
                                                                            <img src="{{ $comment->user->profile_picture }}" alt="Profile Picture" class="comment-profile-picture">
                                                                        </div>
                                                                        <div class="comment-box-body">
                                                                            <div class="comment-box-content">
                                                                                <p class="comment-content__text">{{ $comment->comment }}</p>
                                                                            </div>
                                                                            <div class="comment-meta-details">
                                                                                <span class="comment-username">{{ $comment->user->username }}</span>
                                                                                <span class="comment-time">{{ $comment->created_at->diffForHumans() }}</span>
                                                                            </div>
                                                                        </div>
                                                                    </li>
                                                                @endforeach
                                                            </ul>
                                                        </div>
                                                        <div class="new-comment-editor">
                                                            @if (auth()->check())
                                                            <form action="{{ route('debate.comment', $pro->id) }}" method="POST" class="comment-form">
                                                                @csrf
                                                                <input type="text" class="new-comment-editor__input-field" name="comment" placeholder="Enter your comment" required>
                                                                <button type="submit" class="new-comment-editor__submit-button">&#8594;</button>
                                                            </form>
                                                            @else
                                                                <button class="comment-login-btn" onclick="openLoginForm()">Please log in to write comments</button>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <div id="votesContainerPro{{ $pro->id }}" class="votes-drafts-container" style="display:none;">
                                                        @if($pro->userVoted())
                                                            <form action="{{ route('debate.deleteVote', $pro->id) }}" method="POST">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="btn btn-danger">&#8634;</button>
                                                            </form>
                                                        @endif

                                                        <canvas id="votesChartPro{{ $pro->id }}" width="200" height="40"></canvas>

                                                        @if ($pro->voting_allowed)
                                                            <form action="{{ route('debate.vote', $pro->id) }}" method="POST">
                                                                @csrf
                                                                <div class="vote-buttons">
                                                                    @for ($i = 0; $i <= 4; $i++)
                                                                        <button type="submit" name="rating" value="{{ $i }}">{{ $i }}</button>
                                                                    @endfor
                                                                </div>
                                                            </form>
                                                        @else
                                                            <p>Enable voting for this debate to allow voting.</p>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                            <div class="column-box column-box--claims column-box--claims-con">
                                <ul class="column__content--cons">
                                    @foreach($cons as $con)
                                        <li class="con-argument">
                                            <div class="actionable-claim-container">
                                                <div class="actionable-claim claims {{ $con->side }}-child-claim">
                                                    <div class="claim-card child-claim-card" data-debate-id="{{ $con->id }}" data-debate-slug="{{ $con->slug }}">
                                                        <div class="claim-header">
                                                            <div class="votes-btn-container">
                                                                {{-- PRGRESS BAR BLADE in views>components>progress-bar.blade.php --}}
                                                                <x-progress-bar :value="$averageVotes['cons'][$con->id]" />
                                                                <button class="votes-btn" data-target="votesContainerCon{{ $con->id }}">Votes</button>
                                                            </div>
                                                            <div class="comments-btn-conaitner">
                                                                <button class="comment-btn">
                                                                    <span class="svg-icon svg-icon-primary svg-icon-2x">
                                                                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                                                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                                                <rect x="0" y="0" width="24" height="24"/>
                                                                                <path d="M16,15.6315789 L16,12 C16,10.3431458 14.6568542,9 13,9 L6.16183229,9 L6.16183229,5.52631579 C6.16183229,4.13107011 7.29290239,3 8.68814808,3 L20.4776218,3 C21.8728674,3 23.0039375,4.13107011 23.0039375,5.52631579 L23.0039375,13.1052632 L23.0206157,17.786793 C23.0215995,18.0629336 22.7985408,18.2875874 22.5224001,18.2885711 C22.3891754,18.2890457 22.2612702,18.2363324 22.1670655,18.1421277 L19.6565168,15.6315789 L16,15.6315789 Z" fill="#000000"/>
                                                                                <path d="M1.98505595,18 L1.98505595,13 C1.98505595,11.8954305 2.88048645,11 3.98505595,11 L11.9850559,11 C13.0896254,11 13.9850559,11.8954305 13.9850559,13 L13.9850559,18 C13.9850559,19.1045695 13.0896254,20 11.9850559,20 L4.10078614,20 L2.85693427,21.1905292 C2.65744295,21.3814685 2.34093638,21.3745358 2.14999706,21.1750444 C2.06092565,21.0819836 2.01120804,20.958136 2.01120804,20.8293182 L2.01120804,18.32426 C1.99400175,18.2187196 1.98505595,18.1104045 1.98505595,18 Z M6.5,14 C6.22385763,14 6,14.2238576 6,14.5 C6,14.7761424 6.22385763,15 6.5,15 L11.5,15 C11.7761424,15 12,14.7761424 12,14.5 C12,14.2238576 11.7761424,14 11.5,14 L6.5,14 Z M9.5,16 C9.22385763,16 9,16.2238576 9,16.5 C9,16.7761424 9.22385763,17 9.5,17 L11.5,17 C11.7761424,17 12,16.7761424 12,16.5 C12,16.2238576 11.7761424,16 11.5,16 L9.5,16 Z" fill="#000000" opacity="0.3"/>
                                                                            </g>
                                                                        </svg>
                                                                    </span>
                                                                    <span class="comment-count">{{ $con->comments->count() > 0 ? $con->comments->count() : '' }}</span>
                                                                </button>
                                                            </div>
                                                        </div>
                                                        <div class="claim-text">
                                                            <p class="claim-text__content">{{ $con->title }}</p>
                                                        </div>
                                                    </div>
                                                    <div class="comment-form-container" style="display:none;">
                                                        <div class="comment-container__header">
                                                            <button type="button" class="close-form-btn close-comment-container">&#10005;</button>
                                                        </div>
                                                        <div class="comment-container__content">
                                                            <ul class="comments-list">
                                                                @foreach($con->comments as $comment)
                                                                    <li class="comment-box">
                                                                        <div class="comment-identity-avatar">
                                                                            <img src="{{ $comment->user->profile_picture }}" alt="Profile Picture" class="comment-profile-picture">
                                                                        </div>
                                                                        <div class="comment-box-body">
                                                                            <div class="comment-box-content">
                                                                                <p class="comment-content__text">{{ $comment->comment }}</p>
                                                                            </div>
                                                                            <div class="comment-meta-details">
                                                                                <span class="comment-username">{{ $comment->user->username }}</span>
                                                                                <span class="comment-time">{{ $comment->created_at->diffForHumans() }}</span>
                                                                            </div>
                                                                        </div>
                                                                    </li>
                                                                @endforeach
                                                            </ul>
                                                        </div>
                                                        <div class="new-comment-editor">
                                                            @if (auth()->check())
                                                            <form action="{{ route('debate.comment', $con->id) }}" method="POST" class="comment-form">
                                                                @csrf
                                                                <input type="text" class="new-comment-editor__input-field" name="comment" placeholder="Enter your comment" required>
                                                                <button type="submit" class="new-comment-editor__submit-button">&#8594;</button>
                                                            </form>
                                                            @else
                                                                <button class="comment-login-btn" onclick="openLoginForm()">Please log in to write comments</button>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <div id="votesContainerCon{{ $con->id }}" class="votes-drafts-container" style="display:none;">
                                                        @if($con->userVoted())
                                                            <form action="{{ route('debate.deleteVote', $con->id) }}" method="POST">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="btn btn-danger">&#8634;</button>
                                                            </form>
                                                        @endif

                                                        <canvas id="votesChartCon{{ $con->id }}" width="200" height="40"></canvas>
                                                        
                                                        @if ($con->voting_allowed)
                                                            <form action="{{ route('debate.vote', $con->id) }}" method="POST">
                                                                @csrf
                                                                <div class="vote-buttons">
                                                                    @for ($i = 0; $i <= 4; $i++)
                                                                        <button type="submit" name="rating" value="{{ $i }}">{{ $i }}</button>
                                                                    @endfor
                                                                </div>
                                                            </form>
                                                        @else
                                                            <p>Enable voting for this debate to allow voting.</p>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>

                </main>
            </div>
        </div>
    </div>
</section>

@endsection

<script>
    document.addEventListener('DOMContentLoaded', function() {

        function closeAllCommentForms() {
            document.querySelectorAll('.comment-form-container').forEach(formContainer => {
                formContainer.style.display = 'none';
            });
        }

        document.querySelectorAll('.comment-btn').forEach(button => {
            button.addEventListener('click', function() {
                const formContainer = this.closest('.claims').querySelector('.comment-form-container');
                // Close all open comment forms before opening the clicked one
                closeAllCommentForms();
                // Open the clicked comment form
                formContainer.style.display = 'block';
            });
        });

        document.querySelectorAll('.close-form-btn').forEach(button => {
            button.addEventListener('click', function() {
                const formContainer = this.closest('.comment-form-container');
                formContainer.style.display = 'none';
            });
        });

        // Event listener to close the comment form when clicking outside of it
        document.addEventListener('click', function(event) {
            if (!event.target.closest('.comment-form-container') && !event.target.closest('.comment-btn')) {
                closeAllCommentForms();
            }
        });

            // Updated function for handling comments button clicks
            document.querySelectorAll('.comment-btn').forEach(button => {
                button.addEventListener('click', function() {
                    if (!authCheck()) {
                        openLoginForm();
                    }
                });
            });

            // Function to check if user is authenticated
            function authCheck() {
                return button.getAttribute('data-authenticated') === '1';
            }

        // Function to handle the selection of a claim
        function selectClaim(claimId, claimSlug) {
            // Clear the current selection
            clearSelection();

            // Find the selected claim card element
            const selectedClaimCard = document.querySelector(`.claim-card[data-debate-id="${claimId}"]`);

            // Update the URL with the selected claim's slug
            updateURL(claimSlug);

            // Show the selected claim card
            selectedClaimCard.classList.add('selected');

            // Find the parent claim container
            const claimContainer = selectedClaimCard.closest('.claims');

            // Show the claim container
            claimContainer.classList.add('show');

            // Find and show the child claims
            showChildClaims(claimId);
        }

        // Function to clear the current selection
        function clearSelection() {
            // Remove the 'selected' class from all claim cards
            const selectedClaimCards = document.querySelectorAll('.claim-card.selected');
            selectedClaimCards.forEach(card => card.classList.remove('selected'));

            // Hide all claim containers
            const claimContainers = document.querySelectorAll('.claims.show');
            claimContainers.forEach(container => container.classList.remove('show'));

            // Hide all child claims
            const childClaimContainers = document.querySelectorAll('.child-claims.show');
            childClaimContainers.forEach(container => container.classList.remove('show'));
        }

        // Function to show the child claims of the selected claim
        function showChildClaims(claimId) {
            // Find the child claim containers for the selected claim
            const childClaimContainers = document.querySelectorAll(`.child-claims[data-parent-id="${claimId}"]`);

            // Show the child claim containers
            childClaimContainers.forEach(container => container.classList.add('show'));
        }

        // Function to update the URL with the selected claim's slug
        function updateURL(slug) {
            const newURL = `${window.location.origin}/${slug}`;
            window.history.pushState({}, '', newURL);
        }

        document.querySelectorAll('.claim-card').forEach(card => {
            card.addEventListener('click', (event) => {
                // Check if the click occurred on the claim header
                if (!event.target.closest('.claim-header')) {
                    const claimId = card.dataset.debateId;
                    const claimSlug = card.dataset.debateSlug;
                    const newURL = `${window.location.origin}/debate/${claimSlug}?active=${claimId}`;
                    window.location.href = newURL;
                }
            });
        });
    });

    // ##### VOTES FUNCTIONALITY

    document.addEventListener("DOMContentLoaded", function() {
        const voteButtons = document.querySelectorAll('.votes-btn');
        voteButtons.forEach(button => {
            button.addEventListener('click', function() {
                // Close any open forms
                const openForms = document.querySelectorAll('.votes-drafts-container');
                openForms.forEach(form => {
                    if (form.id !== this.getAttribute('data-target')) {
                        form.style.display = 'none';
                    }
                });

                // Open or close the clicked form
                const target = document.getElementById(this.getAttribute('data-target'));
                target.style.display = target.style.display === 'none' ? 'block' : 'none';
            });
        });

        // Function to initialize the chart
        function initializeChart(canvasId, votesCount) {
            var ctx = document.getElementById(canvasId).getContext('2d');
            var colors = [
                '#7dbbf4',
                '#4197e7',
                '#2985db',
                '#2478c6',
                '#1d67ac'
            ];

            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: ['0', '1', '2', '3', '4'],
                    datasets: [{
                        label: 'Votes Count',
                        data: votesCount,
                        backgroundColor: colors,
                        borderColor: colors,
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        x: {
                            display: true,
                        },
                        y: {
                            display: false,
                        }
                    }
                }
            });
        }

        // Initialize charts for the main debate
        var votesCount = {!! json_encode($votesCount) !!};
        initializeChart('votesChart', votesCount);

        // Initialize charts for ancestors
        @foreach($ancestorsVotesCount as $ancestorId => $votesCount)
            initializeChart('votesChartAncestor{{ $ancestorId }}', {!! json_encode($votesCount) !!});
        @endforeach

        // Initialize charts for pros
        @foreach($prosVotesCount as $proId => $votesCount)
            initializeChart('votesChartPro{{ $proId }}', {!! json_encode($votesCount) !!});
        @endforeach

        // Initialize charts for cons
        @foreach($consVotesCount as $conId => $votesCount)
            initializeChart('votesChartCon{{ $conId }}', {!! json_encode($votesCount) !!});
        @endforeach

        // Close vote forms when clicking outside of them
        document.body.addEventListener('click', function(event) {
            if (!event.target.classList.contains('votes-btn') && !event.target.closest('.votes-drafts-container')) {
                const openForms = document.querySelectorAll('.votes-drafts-container');
                openForms.forEach(form => {
                    form.style.display = 'none';
                });
            }
        });
    });


</script>

