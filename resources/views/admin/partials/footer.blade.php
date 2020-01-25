<footer class="govuk-footer" role="contentinfo">
  <div class="govuk-width-container ">
    @if($navigation ?? false)
      <div class="govuk-footer__navigation">
        @foreach($navigation as $item)
          <div class="govuk-footer__section">
            <h2 class="govuk-footer__heading govuk-heading-m">
              {{ $item['title'] }}
            </h2>

            <ul class="govuk-footer__list govuk-footer__list--columns-2">
              @foreach($item['items'] as $subItem)
                <li class="govuk-footer__list-item">
                  <a class="govuk-footer__link" href="{{ $subItem['url'] }}">
                    {{ $subItem['text'] }}
                  </a>
                </li>
              @endforeach
            </ul>
          </div>
        @endforeach
      </div>

      <hr class="govuk-footer__section-break" />
    @endif

    <div class="govuk-footer__meta">
      <div class="govuk-footer__meta-item govuk-footer__meta-item--grow">
        <h2 class="govuk-visually-hidden">
          {{ $meta['visuallyHiddenTitle'] ?? 'Support links' }}
        </h2>

        <ul class="govuk-footer__inline-list">
          @foreach($meta['items'] as $item)
            <li class="govuk-footer__inline-list-item">
              <a class="govuk-footer__link" href="{{ $item['url'] }}">
                {{ $item['text'] }}
              </a>
            </li>
          @endforeach
        </ul>
      </div>
    </div>
  </div>
</footer>
