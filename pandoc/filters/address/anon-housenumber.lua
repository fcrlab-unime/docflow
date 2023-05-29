function Span(span)
if span.classes:includes('personaldata') and span.classes:includes('address') and span.classes:includes('housenumber') then
  span.content = pandoc.Str('xx')
  local value, position = span.classes:find('housenumber')
  span.classes:remove(position)
  local value, position = span.classes:find('address')
  span.classes:remove(position)
end
return span
end