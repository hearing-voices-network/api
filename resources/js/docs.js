import SwaggerUI from 'swagger-ui';

SwaggerUI({
  dom_id: '#swagger-ui',
  url: '/docs/openapi.json',
  defaultModelsExpandDepth: -1,
  docExpansion: 'none'
});
