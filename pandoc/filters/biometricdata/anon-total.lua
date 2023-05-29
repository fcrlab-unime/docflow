function Span(span)
if span.classes:includes('personaldata') and span.classes:includes('biometricdata') then
  span.content = pandoc.Str('xxxxxx')
  local value, position = span.classes:find('biometricdata')
  span.classes:remove(position)
end
return span
end