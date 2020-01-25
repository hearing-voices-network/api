<header class="govuk-header " role="banner" data-module="govuk-header">
  <div class="govuk-header__container govuk-width-container">
    <div class="govuk-header__logo">
      <a
        href="{{ $homepageUrl ?? route('landing') }}"
        class="govuk-header__link govuk-header__link--homepage"
      >
        <span class="govuk-header__logotype">
          <span class="govuk-header__logotype-text">
            {{ $serviceName }}
          </span>
        </span>
      </a>
    </div>

    <div class="govuk-header__content">
      @if($navigation ?? false)
        <button
          type="button"
          role="button"
          class="govuk-header__menu-button govuk-js-header-toggle"
          aria-controls="navigation"
          aria-label="Show or hide Top Level Navigation"
        >
          Menu
        </button>

        <nav>
          <ul
            id="navigation"
            class="govuk-header__navigation"
            aria-label="Top Level Navigation"
          >
            @foreach($navigation as $item)
              <li class="govuk-header__navigation-item">
                <a href="{{ $item['url'] }}" class="govuk-header__link">
                  {{ $item['text'] }}
                </a>
              </li>
            @endforeach
          </ul>
        </nav>
      @endif
    </div>
  </div>
</header>
