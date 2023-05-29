function Span(span)
if span.classes:includes('personaldata') and span.classes:includes('licenseplate') then
  span.content = pandoc.Str('xxxxxx')
  local value, position = span.classes:find('licenseplate')
  span.classes:remove(position)
end
return span
end