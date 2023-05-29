function Span(span)
if span.classes:includes('personaldata') and span.classes:includes('geolocation') then
  span.content = pandoc.Str('xxxxxx')
  local value, position = span.classes:find('geolocation')
  span.classes:remove(position)
end
return span
end