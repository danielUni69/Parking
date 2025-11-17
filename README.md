# Cargar tipo usuario
```sql
NSERT INTO public.tipo_usuario (tipo, created_at, updated_at)
VALUES
    ('admin', NOW(), NOW()),
    ('empleado', NOW(), NOW());
```
